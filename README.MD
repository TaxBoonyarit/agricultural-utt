## How to run project with Docker
```docker
docker compose up || docker-compose up 
```


## Database backup `database/backup.sql`

## Note!!!!
- How to fix "SELECT list is not in GROUP BY clause and contains nonaggregated column .... incompatible with sql_mode=only_full_group_by' get command under run at MYSQL or Databse 
```sql
SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
```