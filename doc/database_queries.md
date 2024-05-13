# Database queries

Some example of database queries

# Simple query

    SELECT * FROM `boards` WHERE 1

# Column selection

    SELECT name, description FROM `boards` WHERE 1

# Simple join

    select *
    from columns, boards
    where board_id = boards.name


    select id, columns.name, boards.name, tasks
    from columns, boards
    where board_id = boards.name
