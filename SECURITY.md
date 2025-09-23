# 🔒 Security Configuration Guide

## Database Security with PDO

This guide explains how to configure your affiliate platform with maximum security using PDO and environment variables.

## 🛡️ Security Features Implemented

### 1. **Enhanced PDO Configuration**
- **Prepared Statements**: All queries use prepared statements to prevent SQL injection
- **Error Handling**: PDO exceptions are properly handled
- **SSL Support**: Optional SSL encryption for database connections
- **Connection Pooling**: Optimized connection management
- **Strict SQL Mode**: Enforces data integrity constraints

### 2. **Environment-Based Configuration**
- **Secure Credentials**: Database credentials stored in `.env` file
- **Production Settings**: Separate configurations for development and production
- **Encryption Keys**: JWT and encryption keys in environment variables
- **Feature Flags**: Security features can be toggled via environment variables

### 3. **Database Security Options**
- **Strict Mode**: `STRICT_TRANS_TABLES` enabled for data integrity
- **SSL Encryption**: Optional SSL connections for production
- **Connection Timeouts**: Configurable connection timeouts
- **Query Monitoring**: Slow query detection and logging

## 📋 Configuration Files

### 1. **Environment Configuration** (`.env`)
```bash
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=affiliate_platform
DB_USERNAME=your_secure_username
DB_PASSWORD=your_secure_password

# Security Options
DB_STRICT_MODE=true
DB_ENGINE=InnoDB
DB_SSL_VERIFY=false

# Connection Pool Settings
DB_POOL_MIN=1
DB_POOL_MAX=10
DB_CONNECT_TIMEOUT=10
```

### 2. **Database Security Configuration** (`config/database-security.php`)
- PDO security options
- SSL configuration
- Connection pooling settings
- SQL mode configuration
- Monitoring and logging settings

### 3. **Enhanced Database Configuration** (`config/database.php`)
- Updated MySQL connection with security options
- PDO attribute configuration
- SSL certificate paths
- Connection pool settings

## 🔧 Security Commands

### Test Database Security
```bash
php artisan db:test-security
```

This command will:
- ✅ Test database connectivity
- ✅ Verify prepared statements
- ✅ Check SQL mode configuration
- ✅ Test SSL connection (if configured)
- ✅ Display security recommendations

### Generate Application Key
```bash
php artisan key:generate
```

### Clear Configuration Cache
```bash
php artisan config:clear
php artisan cache:clear
```

## 🚀 Production Security Checklist

### 1. **Environment Variables**
- [ ] Set strong database passwords
- [ ] Enable SSL certificates for database
- [ ] Set secure JWT secrets
- [ ] Configure reCAPTCHA keys
- [ ] Set up email credentials

### 2. **Database Security**
- [ ] Use dedicated database user (not root)
- [ ] Enable SSL connections
- [ ] Set up database backups
- [ ] Configure connection limits
- [ ] Enable query logging

### 3. **Application Security**
- [ ] Set `APP_DEBUG=false` for production
- [ ] Enable 2FA for admin users
- [ ] Configure rate limiting
- [ ] Set up security headers
- [ ] Enable audit logging

## 🔐 SSL Configuration

### For Production with SSL:
```bash
# Add to .env file
DB_SSL_CA=/path/to/ca-cert.pem
DB_SSL_CERT=/path/to/client-cert.pem
DB_SSL_KEY=/path/to/client-key.pem
DB_SSL_CIPHER=AES256-SHA
DB_SSL_VERIFY=true
```

### MySQL Server SSL Setup:
```sql
-- Create SSL certificates
-- Configure MySQL server for SSL
-- Update user permissions for SSL
GRANT ALL PRIVILEGES ON affiliate_platform.* TO 'affiliate_user'@'%' REQUIRE SSL;
```

## 📊 Security Monitoring

### Database Monitoring Features:
- **Slow Query Detection**: Automatically logs queries over threshold
- **Error Logging**: Database errors are logged with context
- **Connection Monitoring**: Track connection usage and timeouts
- **Security Auditing**: Monitor for suspicious database activity

### Log Files:
- `storage/logs/laravel.log` - Application and database errors
- Database slow query log (if enabled)
- Security audit logs

## 🛠️ Troubleshooting

### Common Issues:

1. **SSL Connection Failed**
   - Verify SSL certificates are valid
   - Check MySQL server SSL configuration
   - Ensure certificate paths are correct

2. **Prepared Statements Not Working**
   - Check PDO configuration
   - Verify MySQL version compatibility
   - Review connection options

3. **Connection Timeout**
   - Increase `DB_CONNECT_TIMEOUT` value
   - Check network connectivity
   - Verify MySQL server status

### Security Test Results:
```
✅ Database connection successful
✅ Prepared statements working correctly
✅ SQL mode is secure
⚠️  SSL connection not detected (for local development)
```

## 📚 Additional Resources

- [Laravel Database Configuration](https://laravel.com/docs/database)
- [PDO Security Best Practices](https://www.php.net/manual/en/pdo.security.php)
- [MySQL Security Guidelines](https://dev.mysql.com/doc/refman/8.0/en/security.html)
- [SSL/TLS Configuration](https://dev.mysql.com/doc/refman/8.0/en/using-encrypted-connections.html)

## 🔄 Updates and Maintenance

### Regular Security Tasks:
1. **Update Dependencies**: Keep Laravel and packages updated
2. **Review Logs**: Monitor security logs regularly
3. **Test Security**: Run security tests periodically
4. **Backup Verification**: Ensure backups are working
5. **Certificate Renewal**: Renew SSL certificates before expiration

---

**Remember**: Security is an ongoing process. Regularly review and update your security configurations as your application grows and evolves.
