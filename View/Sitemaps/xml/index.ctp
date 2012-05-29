<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($pages as $page):?>
    <url>
        <loc><?php echo $this->Html->url($page->url, true); ?></loc>
        <lastmod><?php echo $page->modified->format(DateTime::ATOM) ?></lastmod>
        <changefreq><?php echo $page->changeFreq; ?></changefreq>
        <priority><?php echo $page->priority; ?></priority>
    </url>
    <?php endforeach; ?>
</urlset>
