# clickhouse-pgsql-comparison
```clickhouse
CREATE TABLE IF NOT EXISTS books
(
    isbn String,
    title String,
    country_code String,
    language_code String,
    is_ebook String, -- ClickHouse can't parse "true" to a valid boolean
    average_rating Float32,
    ratings_count UInt32,
    description String,
    `format` String,
    link String,
    `url` String,
    publisher String
) Engine = MergeTree PRIMARY KEY(isbn)
```

download dataset from [here](https://drive.google.com/uc?id=1TLmSvzHvTLLLMjMoQdkx6pBWon-4bli7) (provided by [UCSD](https://sites.google.com/eng.ucsd.edu/ucsdbookgraph/books))