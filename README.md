# Projet laboratoire

## choix techniques

- Symfony
- Doctrine ORM
- Docker
- API orientée JSON
---

## Lancer le projet avec Docker

```bash
docker compose up
```


## Endpoint principal :
  - GET /sample

## json attendue
```
{
  "status": "OK",
  "assigned_samples": [
    {
      "technician_type" => 'SPECIALIST',
      "sample": "Sample 1",
      "priority": "STAT",
      "name": "Charlotte",
      "equipment": "Equpement 2",
      "duration":120 minutes 
    }
  ]
}
```
