create table urls
(
    code varbinary(6) not null,
    url  varchar(255) not null,
    constraint urls_code_uindex
        unique (code)
);
