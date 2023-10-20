# Simple tree builder

Database structure:
```sql
CREATE TABLE `tree` (
  `id` int NOT NULL,
  `parent_id` int NOT NULL,
  `title` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tree`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`,`id`) USING BTREE;

ALTER TABLE `tree`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
```
