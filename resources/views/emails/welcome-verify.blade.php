<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bienvenido a Zendo</title>
</head>
<body style="margin:0;padding:0;background:#0F172A;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#0F172A;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="560" cellpadding="0" cellspacing="0" style="background:#1E293B;border-radius:16px;overflow:hidden;border:1px solid #334155;">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#0F172A 0%,#134e4a 100%);padding:36px 40px;text-align:center;">
              <table cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
                <tr>
                  <td style="background:#14B8A6;width:44px;height:44px;border-radius:10px;text-align:center;vertical-align:middle;">
                    <span style="color:#fff;font-size:22px;font-weight:700;line-height:44px;display:block;">Z</span>
                  </td>
                  <td style="padding-left:10px;vertical-align:middle;">
                    <span style="color:#F1F5F9;font-size:22px;font-weight:700;">Zendo</span>
                  </td>
                </tr>
              </table>
              <p style="margin:0;color:#94A3B8;font-size:14px;">Tu sistema de gestión empresarial</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:40px;">
              <h1 style="margin:0 0 8px;color:#F1F5F9;font-size:24px;font-weight:700;">
                ¡Bienvenido, {{ $user->name }}! 👋
              </h1>
              <p style="margin:0 0 24px;color:#94A3B8;font-size:15px;line-height:1.6;">
                Gracias por registrarte en <strong style="color:#14B8A6;">Zendo</strong>. Estás a un paso de gestionar tu negocio de forma más inteligente.
              </p>

              <div style="background:#0F172A;border-radius:12px;padding:20px;margin-bottom:28px;border:1px solid #334155;">
                <p style="margin:0 0 4px;color:#94A3B8;font-size:12px;text-transform:uppercase;letter-spacing:0.05em;">Tu cuenta</p>
                <p style="margin:0;color:#F1F5F9;font-size:15px;font-weight:600;">{{ $user->fullName() }}</p>
                <p style="margin:4px 0 0;color:#64748B;font-size:13px;">{{ $user->email }}</p>
              </div>

              <p style="margin:0 0 20px;color:#CBD5E1;font-size:15px;line-height:1.6;">
                Para activar tu cuenta y comenzar a usar Zendo, verifica tu dirección de correo haciendo clic en el botón:
              </p>

              <!-- CTA Button -->
              <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
                <tr>
                  <td style="background:#14B8A6;border-radius:10px;">
                    <a href="{{ $verificationUrl }}"
                       style="display:inline-block;padding:14px 32px;color:#fff;font-size:15px;font-weight:600;text-decoration:none;letter-spacing:0.01em;">
                      Verificar mi cuenta →
                    </a>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 8px;color:#64748B;font-size:13px;line-height:1.5;">
                Este enlace expira en <strong>60 minutos</strong>. Si no creaste una cuenta en Zendo, puedes ignorar este correo.
              </p>

              <p style="margin:16px 0 0;color:#475569;font-size:12px;word-break:break-all;">
                Si el botón no funciona, copia este enlace:<br>
                <a href="{{ $verificationUrl }}" style="color:#14B8A6;">{{ $verificationUrl }}</a>
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:20px 40px;border-top:1px solid #334155;text-align:center;">
              <p style="margin:0;color:#475569;font-size:12px;">
                © {{ date('Y') }} Zendo — Diseñado para emprendedores peruanos
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
