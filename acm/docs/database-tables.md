
## Database tables

L2jMobius ACM requires some extra database tables. All the tables are auto-created upon the first run of the application.

### account_login_history
This table stores the IPs of the users each time they login to the ACM. A column `is_game` takes the value `0` if the login is through the website. Feel free to use this table on your Java server and insert the records of your game.

An example is by editing your `/loginserver/LoginController.java`

```java
// Log activity into history
Instant instant = Instant.ofEpochMilli(System.currentTimeMillis());
LocalDateTime localDateTime = LocalDateTime.ofInstant(instant, ZoneId.systemDefault());
DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
String formattedDateTime = localDateTime.format(formatter);
				
PreparedStatement ps_history = con.prepareStatement("INSERT INTO account_login_history (`account`, `ip`, `login_date`) VALUES (?, ?, ?)");
ps_history.setString(1, info.getLogin());
ps_history.setString(2, address);
ps_history.setString(3, formattedDateTime);
ps_history.execute();
```

### acm_donations
This table stores all donation information. It even stores the payment attempts before they are proccesed and completed.

### acm_donation_items
This table stores all the items that are available for donation.

### acm_settings
This table stores all the required settings of the ACM.

### acm_task_manager
This table stores all the scheduled tasks for the Task Manager.