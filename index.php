<?php
/**
 * Generador de Sitemap XML
 * Crea archivos sitemap.xml válidos para motores de búsqueda
 */
header('Content-Type: text/html; charset=utf-8');

$dominio = $_POST['dominio'] ?? '';
$changefreq = $_POST['changefreq'] ?? 'weekly';
$prioridad = $_POST['prioridad'] ?? '0.8';
$xml = '';
$totalUrls = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dominio = rtrim(trim($dominio), '/');
    $urlsRaw = trim($_POST['urls'] ?? '');

    if ($dominio !== '' && $urlsRaw !== '') {
        // Asegurar que el dominio tenga protocolo
        if (!preg_match('/^https?:\/\//', $dominio)) {
            $dominio = 'https://' . $dominio;
        }

        $urls = array_filter(array_map('trim', explode("\n", $urlsRaw)));
        $totalUrls = count($urls);
        $hoy = date('Y-m-d');

        $lineas = [];
        $lineas[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $lineas[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $url = ltrim($url, '/');
            $urlCompleta = $dominio . '/' . $url;
            // Si es solo "/" o vacío, usar el dominio
            if ($url === '' || $url === '/') {
                $urlCompleta = $dominio . '/';
            }

            $lineas[] = '  <url>';
            $lineas[] = '    <loc>' . htmlspecialchars($urlCompleta) . '</loc>';
            $lineas[] = '    <lastmod>' . $hoy . '</lastmod>';
            $lineas[] = '    <changefreq>' . htmlspecialchars($changefreq) . '</changefreq>';
            $lineas[] = '    <priority>' . htmlspecialchars($prioridad) . '</priority>';
            $lineas[] = '  </url>';
        }

        $lineas[] = '</urlset>';
        $xml = implode("\n", $lineas);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Generador de Sitemap XML Online | ConfiguroWeb</title>
<meta name="description" content="Genera archivos sitemap.xml válidos para Google, Bing y otros motores de búsqueda. Gratis y sin registro.">
<meta name="keywords" content="generador sitemap xml, sitemap google, crear sitemap, seo sitemap, mapa del sitio">
<meta property="og:type" content="website">
<meta property="og:title" content="Generador de Sitemap XML Online">
<meta property="og:description" content="Genera archivos sitemap.xml válidos para motores de búsqueda online gratis.">
<link rel="canonical" href="https://demoscweb.com/github/php-generador-sitemap-xml/">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebApplication","name":"Generador Sitemap XML","applicationCategory":"UtilitiesApplication","operatingSystem":"Any","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"},"author":{"@type":"Person","name":"ConfiguroWeb","url":"https://configuroweb.com"}}
</script>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
  <h1>🗺️ Generador de Sitemap XML</h1>
  <p class="subtitle">Crea tu sitemap.xml para Google y Bing</p>
</header>
<main>
  <form method="POST">
    <label for="dominio">Dominio del sitio web</label>
    <input type="text" name="dominio" id="dominio" value="<?php echo htmlspecialchars($dominio); ?>" placeholder="https://midominio.com" required>

    <label for="urls">URLs del sitio (una por línea, relativas al dominio)</label>
    <textarea name="urls" id="urls" rows="8" placeholder="/
/nosotros
/servicios
/blog
/blog/mi-primer-articulo
/contacto" required><?php echo htmlspecialchars($_POST['urls'] ?? ''); ?></textarea>

    <label for="changefreq">Frecuencia de cambio</label>
    <select name="changefreq" id="changefreq">
      <option value="always" <?php if($changefreq==='always') echo 'selected'; ?>>Siempre (always)</option>
      <option value="hourly" <?php if($changefreq==='hourly') echo 'selected'; ?>>Cada hora (hourly)</option>
      <option value="daily" <?php if($changefreq==='daily') echo 'selected'; ?>>Diario (daily)</option>
      <option value="weekly" <?php if($changefreq==='weekly') echo 'selected'; ?>>Semanal (weekly)</option>
      <option value="monthly" <?php if($changefreq==='monthly') echo 'selected'; ?>>Mensual (monthly)</option>
      <option value="yearly" <?php if($changefreq==='yearly') echo 'selected'; ?>>Anual (yearly)</option>
      <option value="never" <?php if($changefreq==='never') echo 'selected'; ?>>Nunca (never)</option>
    </select>

    <label for="prioridad">Prioridad (0.0 - 1.0)</label>
    <select name="prioridad" id="prioridad">
      <option value="1.0" <?php if($prioridad==='1.0') echo 'selected'; ?>>1.0 (Máxima)</option>
      <option value="0.8" <?php if($prioridad==='0.8') echo 'selected'; ?>>0.8 (Alta)</option>
      <option value="0.6" <?php if($prioridad==='0.6') echo 'selected'; ?>>0.6 (Media)</option>
      <option value="0.4" <?php if($prioridad==='0.4') echo 'selected'; ?>>0.4 (Baja)</option>
      <option value="0.2" <?php if($prioridad==='0.2') echo 'selected'; ?>>0.2 (Mínima)</option>
    </select>

    <button type="submit" class="btn-primary">🗺️ Generar Sitemap</button>
  </form>

  <?php if ($xml !== ''): ?>
  <div class="resultados" style="margin-top:1.5rem">
    <h2 style="margin-bottom:.5rem;font-size:1.1rem">Sitemap XML generado (<?php echo $totalUrls; ?> URLs)</h2>
    <pre style="background:#0f172a;padding:1rem;border-radius:var(--radius);font-family:'Cascadia Code',Consolas,monospace;font-size:.8rem;color:#93c5fd;overflow-x:auto;white-space:pre;line-height:1.4;max-height:400px;overflow-y:auto"><code><?php echo htmlspecialchars($xml); ?></code></pre>
    <p style="color:var(--muted);font-size:.8rem;margin-top:.5rem">📋 Copia este código y guárdalo como <strong>sitemap.xml</strong> en la raíz de tu sitio web.</p>
  </div>
  <?php endif; ?>

  <section class="info">
    <h2>¿Qué es un Sitemap XML?</h2>
    <p>Un <strong>sitemap.xml</strong> es un archivo que lista todas las páginas de tu sitio web para que los motores de búsqueda las encuentren e indexen más rápido.</p>
    <p><strong>Ubicación:</strong> Debe estar en <code style="color:#93c5fd">tudominio.com/sitemap.xml</code></p>
    <p><strong>Enviar a Google:</strong> Regístralo en <strong>Google Search Console → Sitemaps</strong></p>
    <p><strong>Límite:</strong> Máximo 50,000 URLs por archivo. Si tienes más, crea un sitemap index.</p>
  </section>
</main>
<footer>
  <p>Desarrollado por <a href="https://configuroweb.com" target="_blank">ConfiguroWeb</a> ·
     <a href="https://appscweb.com/citas/" target="_blank">Sistema de Citas</a> ·
     <a href="https://appscweb.com/negocios/" target="_blank">Gestión de Negocios</a></p>
  <p>&copy; <?php echo date('Y'); ?> ConfiguroWeb</p>
</footer>
<script src="assets/script.js"></script>
</body>
</html>
