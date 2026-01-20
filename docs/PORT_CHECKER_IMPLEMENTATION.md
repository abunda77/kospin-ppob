# Port Checker Implementation

## Overview

The Port Checker feature provides a comprehensive view of all open ports on the system, with special attention to the SSH tunnel proxy port. This implementation is based on the Python `cekport_gui.py` script and has been adapted for the Laravel application.

## Features

1. **System Information Display**
   - Operating System
   - OS Version
   - PHP Version

2. **Proxy Port Monitoring**
   - Checks if the configured proxy port (default: 1080) is open
   - Displays SSH tunnel status
   - Shows process information for the proxy port

3. **Port Categorization**
   - **Common Ports** (< 10000): Displays up to 20 ports with full details
   - **High Ports** (>= 10000): Shows count and first 5 ports if too many

4. **Port Information**
   - Port number
   - Listening address
   - Process ID (PID)
   - Process name

## Implementation Details

### Controller

**File**: `app/Http/Controllers/NetworkConnectionController.php`

The `checkPort()` method implements the following functionality:

1. Retrieves system information (OS, PHP version)
2. Gets all open ports using platform-specific commands:
   - **Windows**: Uses `netstat -ano` and `tasklist`
   - **Unix/Linux**: Uses `ss -tlnp` or `netstat -tlnp`
3. Checks if the proxy port is open
4. Categorizes ports into common (< 10000) and high (>= 10000) ports
5. Returns results to the view

### View

**File**: `resources/views/pages/network/check-port.blade.php`

The view displays:
- System information in a grid layout
- Proxy port status with visual indicators (✅/❌)
- Summary statistics (total ports, common ports, high ports)
- Detailed port tables with hover effects
- Refresh button to re-check ports

### Configuration

**File**: `config/app.php`

```php
'proxy_port' => (int) env('PROXY_PORT', 1080),
```

### Environment Variables

Add to your `.env` file:

```bash
PROXY_PORT=1080
```

## Usage

### Accessing the Port Checker

1. Navigate to **Network Connection** → **Check Port** in the sidebar
2. The page will automatically scan all open ports
3. Click **Refresh Port Check** to re-scan

### Understanding the Results

#### Proxy Port Status

- **✅ Port X is OPEN**: SSH tunnel is running successfully
  - Shows process name, PID, and listening address
  
- **❌ Port X is CLOSED**: SSH tunnel is NOT running
  - Suggests starting the SSH tunnel

#### Port Tables

**Common Ports (< 10000)**
- Shows up to 20 ports
- Typically includes web servers (80, 443), databases (3306, 5432), etc.

**High Ports (>= 10000)**
- Shows count of high ports
- Displays first 5 if there are many
- Often used by applications and services

## Platform-Specific Behavior

### Windows

Uses the following commands:
- `netstat -ano | findstr LISTENING` - Get listening ports
- `tasklist /FI "PID eq {pid}" /FO CSV /NH` - Get process name by PID

### Unix/Linux

Uses the following commands:
- `ss -tlnp` (preferred) or `netstat -tlnp` (fallback)
- Process information is included in the output

## Security Considerations

1. **Authentication Required**: Users must be logged in
2. **Permission Required**: Users need the `network.view` permission
3. **Shell Execution**: The feature uses `shell_exec()` to run system commands
   - Commands are predefined and not user-controlled
   - Output is parsed and sanitized before display

## Testing

**File**: `tests/Feature/NetworkConnection/CheckPortTest.php`

Run the tests:

```bash
php artisan test --filter=CheckPortTest
```

Test coverage includes:
- Authentication and authorization
- System information display
- Proxy port checking
- Port categorization
- Data structure validation
- Permission enforcement

## Troubleshooting

### No Ports Detected

**Possible Causes:**
- Insufficient permissions to run system commands
- Commands not available on the system
- Firewall or security software blocking access

**Solutions:**
- Ensure PHP has permission to execute shell commands
- Check if `netstat` (Windows) or `ss`/`netstat` (Unix) are available
- Run the application with appropriate permissions

### Proxy Port Always Shows as Closed

**Possible Causes:**
- SSH tunnel is not running
- Different port number configured
- Port is bound to a specific interface (not 0.0.0.0 or *)

**Solutions:**
- Start the SSH tunnel
- Verify `PROXY_PORT` in `.env` matches your tunnel configuration
- Check SSH tunnel binding address

### Process Name Shows "Unknown"

**Possible Causes:**
- Insufficient permissions to query process information
- Process terminated between port scan and process query

**Solutions:**
- Run with elevated permissions if needed
- This is a minor issue and doesn't affect port detection

## Related Features

- [IP Checker](IP_CHECKER_IMPLEMENTATION.md) - Check current public IP address
- Network Connection Menu - Access all network-related tools

## Future Enhancements

Potential improvements:
1. Real-time port monitoring with WebSockets
2. Port usage history and analytics
3. Automatic SSH tunnel restart if port is closed
4. Custom port monitoring (add specific ports to watch)
5. Email/notification alerts when critical ports go down

## References

- Original Python script: `cekport_gui.py`
- Laravel Documentation: [Configuration](https://laravel.com/docs/12.x/configuration)
- PHP `shell_exec()`: [PHP Manual](https://www.php.net/manual/en/function.shell-exec.php)
