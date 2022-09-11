# Development

## First run

```bash
make up && make install
```

## Open project

- Symfony Host [http://localhost:8080/](http://localhost:8080/)
- EasyAdmin [http://localhost:8080/admin](http://localhost:8080/admin)
```
Admin: admin@mail.com
Pass: password123
```
## Dev commands

```bash
make up # start docker compose

make install # run composer, migrations, fixtures
make stop # stop docker compose
make down # stop and and removes containers, networks, volumes, and images

make php # bash to php container
```

## Troubleshooting

First of all try to `make down && make up && make install`
