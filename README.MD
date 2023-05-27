## How to run project with Docker
```docker
docker compose up || docker-compose up 
```
## Note!!!!
- This project uses API from google map, if you will use it should get API from google map.
- Database backup `database/backup.sql`
- How to fix "SELECT list is not in GROUP BY clause and contains nonaggregated column .... incompatible with sql_mode=only_full_group_by' get command under run at MYSQL or Databse 
```sql
SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
```

## Environments
```php
$_ENV["MYSQL_DATABASE"];
$_ENV["MYSQL_USER"];
$_ENV["MYSQL_PASSWORD"];
$_ENV["MYSQL_DATABASE"];
```


### Account Admin 
```json
{
    "username": "admin",
    "password" : "12345678"
}
```