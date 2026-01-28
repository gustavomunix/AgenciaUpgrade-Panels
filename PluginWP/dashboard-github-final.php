<?php
/**
 * Plugin Name: Dashboard GitHub - Final
 * Description: Dashboard do GitHub com suporte a múltiplos CSS (Bricks/Fancy)
 * Version: 6.0
 * Author: Gustavo Muniz
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

class ML_Dashboard_GitHub_Final {

    private $option_html_url = 'ml_dashboard_html_url';
    private $option_css_urls = 'ml_dashboard_css_urls'; // Array de URLs
    private $cache_key_html = 'ml_dashboard_html_v3';
    private $cache_key_css = 'ml_dashboard_css_v3_';
    private $cache_time = 43200; // 12 horas

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_dashboard_setup', [$this, 'setup_dashboard'], 999);
        add_filter('get_user_option_meta-box-order_dashboard', '__return_empty_array');
        add_filter('get_user_option_metaboxhidden_dashboard', '__return_empty_array');
        add_action('admin_head-index.php', [$this, 'inject_custom_css']);
        add_action('admin_head-index.php', [$this, 'custom_styles']);
        add_action('update_option_' . $this->option_html_url, [$this, 'clear_all_cache']);
        add_action('update_option_' . $this->option_css_urls, [$this, 'clear_all_cache']);
    }

    public function add_settings_page() {
        add_options_page(
            'Dashboard GitHub',
            'Dashboard GitHub',
            'manage_options',
            'ml-dashboard-github',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('ml_dashboard_settings', $this->option_html_url, [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ]);

        register_setting('ml_dashboard_settings', $this->option_css_urls, [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_css_urls'],
            'default' => []
        ]);

        add_settings_section(
            'ml_dashboard_main',
            'Configuração do Dashboard',
            [$this, 'settings_section_callback'],
            'ml-dashboard-github'
        );

        add_settings_field(
            'ml_dashboard_html_url_field',
            'URL do HTML (GitHub Raw) *',
            [$this, 'html_url_field_callback'],
            'ml-dashboard-github',
            'ml_dashboard_main'
        );

        add_settings_field(
            'ml_dashboard_css_urls_field',
            'URLs de CSS (Opcional - Até 3)',
            [$this, 'css_urls_field_callback'],
            'ml-dashboard-github',
            'ml_dashboard_main'
        );
    }

    public function sanitize_css_urls($input) {
        if (!is_array($input)) {
            return [];
        }

        // Remove valores vazios e sanitiza URLs
        $sanitized = array_filter(array_map('esc_url_raw', array_map('trim', $input)));

        // Limita a 3 URLs
        return array_slice($sanitized, 0, 3);
    }

    public function settings_section_callback() {
        ?>
        <p>Configure o HTML do GitHub e opcionalmente adicione até 3 URLs de CSS (ex: Bricks global-variables.min.css).</p>
        <div class="notice notice-info inline">
            <p><strong>💡 Como funciona:</strong> O HTML tem valores CSS padrão. Se adicionar URLs de CSS, elas sobrescrevem as variáveis em cascata.</p>
        </div>
        <?php
    }

    public function html_url_field_callback() {
        $value = get_option($this->option_html_url, '');
        ?>
        <input type="url"
               name="<?php echo esc_attr($this->option_html_url); ?>"
               value="<?php echo esc_attr($value); ?>"
               class="large-text"
               placeholder="https://raw.githubusercontent.com/usuario/repo/main/painel.html"
               required />
        <p class="description">
            <strong>URL raw do GitHub</strong> - Cache de 12 horas<br>
            <strong>Exemplo:</strong> <code>https://raw.githubusercontent.com/usuario/repo/main/PanelWooCommerce-Final.html</code>
        </p>
        <?php
        if (!empty($value)) {
            $cached = get_transient($this->cache_key_html);
            if ($cached !== false) {
                $size_kb = number_format(strlen($cached) / 1024, 2);
                echo '<p class="description" style="color: #46b450; font-weight: 600;">';
                echo '✓ Cache ativo (' . $size_kb . ' KB)';
                echo '</p>';
            }
        }
    }

    public function css_urls_field_callback() {
        $urls = get_option($this->option_css_urls, []);
        $urls = array_pad($urls, 3, ''); // Garante 3 campos
        ?>
        <div id="css-urls-repeater">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <div style="margin-bottom: 10px;">
                    <input type="url"
                           name="<?php echo esc_attr($this->option_css_urls); ?>[]"
                           value="<?php echo esc_attr($urls[$i]); ?>"
                           class="large-text"
                           placeholder="https://seusite.com/wp-content/uploads/bricks/css/global-variables.min.css" />
                    <p class="description">
                        <strong>CSS #<?php echo $i + 1; ?></strong> - Carregado em ordem (cascata CSS)
                    </p>
                </div>
            <?php endfor; ?>
        </div>
        <p class="description" style="margin-top: 15px;">
            <strong>🎨 Exemplos de uso:</strong><br>
            • <strong>CSS 1:</strong> <code><?php echo home_url('/wp-content/uploads/bricks/css/global-variables.min.css'); ?></code><br>
            • <strong>CSS 2:</strong> <code><?php echo home_url('/wp-content/uploads/bricks/css/color-palettes.min.css'); ?></code><br>
            • <strong>CSS 3:</strong> <code>https://raw.githubusercontent.com/usuario/repo/main/custom.css</code>
        </p>
        <p class="description">
            <strong>💡 Dica:</strong> Se deixar vazio, o HTML usa variáveis padrão inline (funciona standalone).
        </p>
        <?php
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        settings_errors();

        $html_url = get_option($this->option_html_url, '');
        $css_urls = get_option($this->option_css_urls, []);
        $css_urls = array_filter($css_urls); // Remove vazios
        $cache_html = get_transient($this->cache_key_html);

        ?>
        <div class="wrap">
            <h1>⚡ Dashboard GitHub - Final</h1>

            <?php if (!empty($html_url)): ?>
                <div class="notice notice-success inline">
                    <p>
                        <strong>✓ Dashboard configurado!</strong>
                        HTML: <?php echo $cache_html !== false ? '✓' : '○'; ?> |
                        CSS: <?php echo !empty($css_urls) ? count($css_urls) . ' arquivo(s)' : 'Standalone'; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="notice notice-warning inline">
                    <p><strong>⚠ Configure a URL do HTML para começar.</strong></p>
                </div>
            <?php endif; ?>

            <form action="options.php" method="post">
                <?php
                settings_fields('ml_dashboard_settings');
                do_settings_sections('ml-dashboard-github');
                submit_button('Salvar e Limpar Cache');
                ?>
            </form>

            <hr>

            <div class="card">
                <h2>🎯 Sistema de Cascata CSS</h2>
                <p>O sistema funciona em camadas, permitindo máxima flexibilidade:</p>

                <h3>📊 Ordem de Carregamento:</h3>
                <ol>
                    <li><strong>HTML Inline:</strong> Variáveis CSS padrão (roxo moderno)</li>
                    <li><strong>CSS #1:</strong> Sobrescreve inline (ex: global-variables.min.css do Bricks)</li>
                    <li><strong>CSS #2:</strong> Sobrescreve CSS #1 (ex: color-palettes.min.css do Bricks)</li>
                    <li><strong>CSS #3:</strong> Sobrescreve CSS #2 (ex: customizações extras)</li>
                </ol>

                <h3>✨ Exemplos de Uso:</h3>

                <h4>Exemplo 1: Standalone (Sem CSS)</h4>
                <ul>
                    <li>HTML: <code>PanelWooCommerce-Final.html</code></li>
                    <li>CSS 1, 2, 3: <em>(vazio)</em></li>
                    <li>Resultado: Painel roxo moderno (valores inline do HTML)</li>
                </ul>

                <h4>Exemplo 2: Com Bricks (Recomendado)</h4>
                <ul>
                    <li>HTML: <code>PanelWooCommerce-Final.html</code></li>
                    <li>CSS 1: <code>global-variables.min.css</code> (spacing, typography)</li>
                    <li>CSS 2: <code>color-palettes.min.css</code> (cores do cliente)</li>
                    <li>CSS 3: <em>(vazio)</em></li>
                    <li>Resultado: Painel com identidade visual do cliente!</li>
                </ul>

                <h4>Exemplo 3: Bricks + Customização</h4>
                <ul>
                    <li>HTML: <code>PanelWooCommerce-Final.html</code></li>
                    <li>CSS 1: <code>global-variables.min.css</code></li>
                    <li>CSS 2: <code>color-palettes.min.css</code></li>
                    <li>CSS 3: <code>custom-dashboard.css</code> (ajustes finos)</li>
                    <li>Resultado: Totalmente personalizado!</li>
                </ul>
            </div>

            <div class="card">
                <h2>⚡ Performance & Cache</h2>

                <table class="widefat" style="max-width: 600px;">
                    <thead>
                        <tr>
                            <th>Recurso</th>
                            <th>Cache</th>
                            <th>Tamanho</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>HTML</td>
                            <td><?php echo $cache_html !== false ? '✓ 12h' : '○ Vazio'; ?></td>
                            <td><?php echo $cache_html !== false ? number_format(strlen($cache_html) / 1024, 2) . ' KB' : '-'; ?></td>
                        </tr>
                        <?php foreach ($css_urls as $index => $url): ?>
                        <tr>
                            <td>CSS #<?php echo $index + 1; ?></td>
                            <td><?php
                                $cache_css = get_transient($this->cache_key_css . $index);
                                echo $cache_css !== false ? '✓ 12h' : '○ Vazio';
                            ?></td>
                            <td><?php echo $cache_css !== false ? number_format(strlen($cache_css) / 1024, 2) . ' KB' : '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p style="margin-top: 15px;"><strong>Estimativa:</strong> Com 100 acessos/dia = apenas <strong>2 requisições ao GitHub</strong> (economia de 98%)</p>
            </div>

            <?php if (current_user_can('manage_options')): ?>
            <div class="card">
                <h2>🔄 Gerenciar Cache</h2>
                <p>Após fazer alterações no GitHub ou no CSS, limpe o cache:</p>

                <form method="post" action="">
                    <?php wp_nonce_field('ml_clear_cache', 'ml_cache_nonce'); ?>
                    <button type="submit" name="ml_clear_cache" class="button button-secondary">Limpar Todo o Cache</button>
                </form>

                <?php
                if (isset($_POST['ml_clear_cache']) &&
                    wp_verify_nonce($_POST['ml_cache_nonce'], 'ml_clear_cache')) {
                    $this->clear_all_cache();
                    echo '<div class="notice notice-success inline" style="margin-top: 10px;"><p>✓ Cache limpo! HTML e todos os CSS serão recarregados.</p></div>';
                }
                ?>
            </div>
            <?php endif; ?>

            <div class="card">
                <h2>📋 Como Obter URLs do Bricks</h2>

                <h3>1. Global Variables (Spacing, Typography, etc)</h3>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px;">https://SEUSITE.com/wp-content/uploads/bricks/css/global-variables.min.css?ver=TIMESTAMP</pre>

                <h3>2. Color Palettes (Cores do Cliente)</h3>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px;">https://SEUSITE.com/wp-content/uploads/bricks/css/color-palettes.min.css?ver=TIMESTAMP</pre>

                <p><strong>💡 Dica:</strong> Inspecione o código fonte do site do cliente e procure por "bricks/css" para encontrar as URLs exatas.</p>
            </div>

            <div class="card">
                <h2>🎨 Variáveis CSS Disponíveis</h2>
                <p>O HTML suporta todas essas variáveis (com ou sem CSS externo):</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <h4>Cores</h4>
                        <code>--primary</code>, <code>--primary-dark</code>, <code>--primary-light</code><br>
                        <code>--secondary</code>, <code>--secondary-dark</code><br>
                        <code>--base</code>, <code>--base-dark</code>, <code>--base-light</code><br>
                        <code>--white</code>, <code>--black</code>
                    </div>
                    <div>
                        <h4>Espaçamento</h4>
                        <code>--space-xs</code>, <code>--space-s</code>, <code>--space-m</code><br>
                        <code>--space-l</code>, <code>--space-xl</code>, <code>--space-xxl</code>
                    </div>
                    <div>
                        <h4>Tipografia</h4>
                        <code>--h1</code>, <code>--h2</code>, <code>--h3</code>, <code>--h4</code><br>
                        <code>--text-xs</code>, <code>--text-s</code>, <code>--text-m</code><br>
                        <code>--text-l</code>, <code>--text-xl</code>
                    </div>
                    <div>
                        <h4>Border Radius</h4>
                        <code>--radius</code>, <code>--radius-s</code>, <code>--radius-m</code><br>
                        <code>--radius-l</code>, <code>--radius-pill</code>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function setup_dashboard() {
        global $wp_meta_boxes;
        $wp_meta_boxes['dashboard'] = [];
        remove_action('welcome_panel', 'wp_welcome_panel');

        wp_add_dashboard_widget(
            'ml_dashboard_github',
            'Dashboard Personalizado',
            [$this, 'render_dashboard']
        );
    }

    public function render_dashboard() {
        $cached = get_transient($this->cache_key_html);

        if ($cached !== false) {
            echo '<!-- ⚡ HTML do cache (12h) -->';
            echo $cached;
            return;
        }

        $content = $this->fetch_from_github();

        if (is_wp_error($content)) {
            echo '<div class="notice notice-error inline">';
            echo '<p><strong>Erro ao carregar dashboard:</strong></p>';
            echo '<p>' . esc_html($content->get_error_message()) . '</p>';
            echo '<p><a href="' . admin_url('options-general.php?page=ml-dashboard-github') . '" class="button">Verificar Configurações</a></p>';
            echo '</div>';
            return;
        }

        set_transient($this->cache_key_html, $content, $this->cache_time);
        echo '<!-- 🌐 HTML carregado do GitHub -->';
        echo $content;
    }

    private function fetch_from_github() {
        $html_url = get_option($this->option_html_url, '');

        if (empty($html_url)) {
            return new WP_Error('not_configured', 'Configure a URL do HTML em <a href="' . admin_url('options-general.php?page=ml-dashboard-github') . '">Dashboard GitHub</a>.');
        }

        $response = wp_remote_get($html_url, [
            'timeout' => 30,
            'sslverify' => true,
            'headers' => [
                'Accept' => 'text/html',
                'User-Agent' => 'WordPress/' . get_bloginfo('version')
            ]
        ]);

        if (is_wp_error($response)) {
            return new WP_Error('fetch_error', 'Erro ao buscar HTML: ' . $response->get_error_message());
        }

        if (wp_remote_retrieve_response_code($response) !== 200) {
            return new WP_Error('http_error', 'Erro HTTP ao acessar GitHub. Verifique a URL.');
        }

        $content = wp_remote_retrieve_body($response);

        if (empty($content)) {
            return new WP_Error('empty_content', 'HTML vazio.');
        }

        return '<div class="ml-dashboard-content">' . $content . '</div>';
    }

    public function inject_custom_css() {
        $css_urls = get_option($this->option_css_urls, []);
        $css_urls = array_filter($css_urls);

        if (empty($css_urls)) {
            return;
        }

        foreach ($css_urls as $index => $css_url) {
            $cache_key = $this->cache_key_css . $index;
            $cached = get_transient($cache_key);

            if ($cached !== false) {
                echo "<!-- ⚡ CSS #" . ($index + 1) . " do cache -->\n";
                echo '<style type="text/css">' . $cached . '</style>' . "\n";
                continue;
            }

            $response = wp_remote_get($css_url, [
                'timeout' => 15,
                'sslverify' => true,
                'headers' => ['Accept' => 'text/css']
            ]);

            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $css = wp_remote_retrieve_body($response);
                if (!empty($css)) {
                    set_transient($cache_key, $css, $this->cache_time);
                    echo "<!-- 🌐 CSS #" . ($index + 1) . " carregado -->\n";
                    echo '<style type="text/css">' . $css . '</style>' . "\n";
                }
            }
        }
    }

    public function custom_styles() {
        ?>
        <style type="text/css">
            #dashboard-widgets-wrap { padding-top: 0; }
            #ml_dashboard_github { width: 100%; }
            #ml_dashboard_github .inside { margin: 0; padding: 0; }
            #ml_dashboard_github .postbox-header { display: none; }
            #dashboard-widgets .postbox-container { width: 100% !important; }
            #dashboard-widgets #postbox-container-2,
            #dashboard-widgets #postbox-container-3,
            #dashboard-widgets #postbox-container-4 { display: none; }
            #ml_dashboard_github.postbox { border: none; box-shadow: none; background: transparent; }
            #screen-meta-links { display: none; }
            .ml-dashboard-content { width: 100%; }
        </style>
        <?php
    }

    public function clear_all_cache() {
        delete_transient($this->cache_key_html);

        // Limpa todos os CSS (até 3)
        for ($i = 0; $i < 3; $i++) {
            delete_transient($this->cache_key_css . $i);
        }
    }
}

// Inicializa
new ML_Dashboard_GitHub_Final();
