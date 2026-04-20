# 🧪 Système de Planification de Laboratoire

## Présentation

Ce projet implémente un **algorithme de planification pour un laboratoire**.

Il permet d’assigner des échantillons entrants à :
- un technicien
- un équipement compatible


---

### 👨‍⚕️ Règles d’affectation

Chaque échantillon doit être assigné à :
- 1 technicien
- 1 équipement

Contraintes :
- Le technicien doit correspondre au type de l’échantillon OU être GENERAL
- Les spécialistes sont prioritaires sur les GENERAL
- Un technicien ne peut pas traiter 2 échantillons en même temps
- Un équipement ne peut pas être utilisé simultanément
- Le technicien doit être dans son créneau horaire

---
## 📥 Données d’entrée

```
{
  "samples": [
    {
      "id": "S1",
      "type": "BLOOD",
      "priority": "STAT",
      "arrivalTime": "09:00",
      "analysisTime": 30
    }
  ],
  "technicians": [
    {
      "id": "T1",
      "speciality": "BLOOD",
      "startTime": "08:00",
      "endTime": "16:00"
    }
  ],
  "equipments": [
    {
      "id": "E1",
      "type": "BLOOD",
      "available": true
    }
  ]
}
```
## 📥 Données de sortie
```
{
  "schedule": [
    {
      "technician_type": "SPECIALIST",
      "sampleId": "S1",
      "technicianId": "T1",
      "equipmentId": "E1",
      "startTime": "09:00",
      "endTime": "09:30",
      "priority": "STAT"
    }
  ],
  "metrics": {
    "totalAnalysisTime": 30,
    "makespan": 30,
    "efficiency": 1
  }
}
```
