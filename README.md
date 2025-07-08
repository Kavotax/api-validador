# ✉️ API Validador de Emails

API REST en PHP + Laravel que valida direcciones de correo electrónico de forma robusta:

✅ Verifica la sintaxis (formato)  
✅ Comprueba registros MX del dominio  
✅ Intenta validación SMTP (si el servidor lo permite)

---

## 🚀 **Endpoints**

### `POST /api/validate/email`

Valida una dirección de correo electrónico.

**Parámetros (JSON):**
```json
{
  "email": "correo@ejemplo.com"
}
