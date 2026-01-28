/**
 * Plugin Name: Dashboard Personalizado - GitHub Edition
 * Description: Dashboard customizado com HTML do GitHub e CSS com variáveis personalizadas por cliente
 * Version: 4.0
 * Author: Gustavo Muniz
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

class ML_Custom_Dashboard_GitHub {

    private $option_html_url = 'ml_dashboard_html_url';
    private $option_css_url = 'ml_dashboard_css_url';
    private $cache_key_html = 'ml_dashboard_html_cache';
    private $cache_key_css = 'ml_dashboard_css_cache';
    private $cache_time = 1800; // 30 minutos
    
    public function __construct() {
        // Adiciona página de configurações
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);

        // Configura o dashboard
        add_action('wp_dashboard_setup', [$this, 'setup_dashboard'], 999);

        // Força exibição do widget
        add_filter('get_user_option_meta-box-order_dashboard', '__return_empty_array');
        add_filter('get_user_option_metaboxhidden_dashboard', '__return_empty_array');

        // Injeta CSS customizado no dashboard
        add_action('admin_head-index.php', [$this, 'inject_custom_css']);

        // Aplica estilos do widget
        add_action('admin_head-index.php', [$this, 'custom_styles']);

        // Limpa cache ao salvar configurações
        add_action('update_option_' . $this->option_html_url, [$this, 'clear_cache']);
        add_action('update_option_' . $this->option_css_url, [$this, 'clear_cache_css']);
    }
    
    /**
     * Adiciona página de configurações
     */
    public function add_settings_page() {
        add_options_page(
            'Dashboard Personalizado',
            'Dashboard Personalizado',
            'manage_options',
            'ml-custom-dashboard',
            [$this, 'render_settings_page']
        );
    }
    
    /**
     * Registra as configurações
     */
    public function register_settings() {
        register_setting('ml_dashboard_settings', $this->option_html_url, [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ]);

        register_setting('ml_dashboard_settings', $this->option_css_url, [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ]);

        add_settings_section(
            'ml_dashboard_main',
            'Configuração do Painel',
            [$this, 'settings_section_callback'],
            'ml-custom-dashboard'
        );

        add_settings_field(
            'ml_dashboard_html_url_field',
            'URL do HTML (GitHub Raw)',
            [$this, 'html_url_field_callback'],
            'ml-custom-dashboard',
            'ml_dashboard_main'
        );

        add_settings_field(
            'ml_dashboard_css_url_field',
            'URL do CSS com Variáveis',
            [$this, 'css_url_field_callback'],
            'ml-custom-dashboard',
            'ml_dashboard_main'
        );
    }
    
    /**
     * Texto explicativo
     */
    public function settings_section_callback() {
        echo '<p>Configure URLs do GitHub (raw) para o HTML do painel e CSS com variáveis personalizadas. Perfeito para ter painéis diferentes por cliente!</p>';
    }

    /**
     * Campo da URL do HTML
     */
    public function html_url_field_callback() {
        $value = get_option($this->option_html_url, '');
        ?>
        <input type="url"
               name="<?php echo esc_attr($this->option_html_url); ?>"
               value="<?php echo esc_attr($value); ?>"
               class="large-text"
               placeholder="https://raw.githubusercontent.com/usuario/repo/main/painel.html" />
        <p class="description">
            <strong>📄 HTML do Painel</strong><br>
            Use a URL <strong>raw</strong> do GitHub para o arquivo HTML.<br>
            <strong>Exemplo:</strong> <code>https://raw.githubusercontent.com/usuario/repo/main/painel-woocommerce.html</code>
        </p>
        <?php
        if (!empty($value)) {
            // Testa se é URL do GitHub raw
            if (strpos($value, 'raw.githubusercontent.com') !== false) {
                echo '<p class="description" style="color: #46b450; font-weight: 600;">';
                echo '✓ URL GitHub Raw válida';
                echo '</p>';
            } else {
                echo '<p class="description" style="color: #f0a000;">';
                echo '⚠ Use a URL <strong>raw</strong> do GitHub para melhor performance';
                echo '</p>';
            }
        }
        ?>
        <?php
    }

    /**
     * Campo da URL do CSS
     */
    public function css_url_field_callback() {
        $value = get_option($this->option_css_url, '');
        ?>
        <input type="url"
               name="<?php echo esc_attr($this->option_css_url); ?>"
               value="<?php echo esc_attr($value); ?>"
               class="large-text"
               placeholder="https://seusite.com/wp-content/uploads/bricks/css/color-palettes.min.css" />
        <p class="description">
            <strong>🎨 CSS com Variáveis do Cliente</strong><br>
            URL do CSS com variáveis personalizadas (ex: Bricks color palettes ou Fancy Framework).<br>
            <strong>Exemplo Bricks:</strong> <code>https://seusite.com/wp-content/uploads/bricks/css/color-palettes.min.css</code><br>
            <strong>Exemplo GitHub:</strong> <code>https://raw.githubusercontent.com/usuario/repo/main/fancy-framework-complete.css</code>
        </p>
        <?php
        if (!empty($value)) {
            echo '<p class="description" style="color: #46b450; font-weight: 600;">';
            echo '✓ CSS customizado será injetado no dashboard';
            echo '</p>';
        }
        ?>
        <?php
    }
    
    /**
     * Renderiza a página de configurações
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        settings_errors();

        // Informações de performance
        $html_url = get_option($this->option_html_url, '');
        $css_url = get_option($this->option_css_url, '');

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php if (!empty($html_url)): ?>
                <div class="notice notice-success inline">
                    <p><strong>✓ Dashboard Configurado!</strong> HTML carregado do GitHub com cache de 30 minutos.</p>
                </div>
            <?php else: ?>
                <div class="notice notice-warning inline">
                    <p><strong>⚠ Configure a URL do HTML</strong> para ativar o dashboard personalizado.</p>
                </div>
            <?php endif; ?>

            <form action="options.php" method="post">
                <?php
                settings_fields('ml_dashboard_settings');
                do_settings_sections('ml-custom-dashboard');
                submit_button('Salvar Configurações');
                ?>
            </form>

            <hr>

            <div class="card">
                <h2>🎯 Dashboard Personalizado por Cliente</h2>
                <p>Sistema inteligente que permite ter painéis diferentes usando GitHub e CSS customizado!</p>

                <h3>📋 Como funciona:</h3>
                <ol>
                    <li><strong>HTML do GitHub Raw</strong> - Hospede o HTML no GitHub e use a URL raw</li>
                    <li><strong>CSS com Variáveis</strong> - Use Bricks color palettes, Fancy Framework ou qualquer CSS customizado</li>
                    <li><strong>Cache Inteligente</strong> - 30 minutos de cache para performance</li>
                    <li><strong>Multi-Cliente</strong> - Crie repos diferentes para cada cliente!</li>
                </ol>

                <h3>🚀 Configuração Passo a Passo:</h3>
                <ol>
                    <li>Crie um repositório no GitHub (pode ser privado ou público)</li>
                    <li>Faça upload do arquivo HTML do painel</li>
                    <li>Obtenha a URL <strong>raw</strong> do arquivo: <code>https://raw.githubusercontent.com/usuario/repo/main/arquivo.html</code></li>
                    <li>Cole a URL no campo <strong>"URL do HTML"</strong></li>
                    <li>Se usar Bricks ou Fancy Framework, cole a URL do CSS com variáveis</li>
                    <li>Salve e acesse o <a href="<?php echo admin_url(); ?>">Dashboard</a></li>
                </ol>

                <h3>🎨 Exemplos de CSS com Variáveis:</h3>
                <ul>
                    <li><strong>Bricks:</strong> <code><?php echo home_url('/wp-content/uploads/bricks/css/color-palettes.min.css'); ?></code></li>
                    <li><strong>GitHub (Fancy):</strong> <code>https://raw.githubusercontent.com/usuario/repo/main/fancy-framework-complete.css</code></li>
                    <li><strong>CDN:</strong> Qualquer URL pública com CSS</li>
                </ul>

                <h3>⚡ Vantagens:</h3>
                <ul>
                    <li>✓ Sem necessidade de upload no servidor</li>
                    <li>✓ Versionamento automático pelo Git</li>
                    <li>✓ Um repo por cliente = fácil manutenção</li>
                    <li>✓ CSS com variáveis = personalização rápida</li>
                    <li>✓ Cache de 30min = performance excelente</li>
                </ul>
            </div>

            <?php if (current_user_can('manage_options')): ?>
            <div class="card">
                <h3>🔄 Limpar Cache</h3>
                <p>Após fazer alterações no GitHub, limpe o cache para ver as mudanças imediatamente:</p>
                <form method="post" action="">
                    <?php wp_nonce_field('ml_clear_cache', 'ml_cache_nonce'); ?>
                    <button type="submit" name="ml_clear_cache" class="button">Limpar Cache do Dashboard</button>
                </form>

                <?php
                if (isset($_POST['ml_clear_cache']) &&
                    wp_verify_nonce($_POST['ml_cache_nonce'], 'ml_clear_cache')) {
                    $this->clear_cache();
                    $this->clear_cache_css();
                    echo '<div class="notice notice-success inline" style="margin-top: 10px;"><p>✓ Cache limpo com sucesso! HTML e CSS serão recarregados.</p></div>';
                }
                ?>
            </div>
            <?php endif; ?>

            <div class="card">
                <h3>💡 Dica Pro:</h3>
                <p>Crie um repositório para cada cliente com o seguinte padrão:</p>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
├── cliente-abc/
│   ├── painel-woocommerce.html
│   ├── fancy-variables.css
│   └── README.md
├── cliente-xyz/
│   ├── painel-custom.html
│   ├── fancy-variables.css
│   └── README.md</pre>
                <p>Assim você mantém tudo organizado e pode fazer deploy instantâneo apenas mudando a URL!</p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Configura o dashboard
     */
    public function setup_dashboard() {
        global $wp_meta_boxes;
        
        $wp_meta_boxes['dashboard'] = [];
        remove_action('welcome_panel', 'wp_welcome_panel');
        
        wp_add_dashboard_widget(
            'ml_custom_dashboard',
            'Painel de Controle',
            [$this, 'render_dashboard_widget']
        );
    }
    
    /**
     * Renderiza o dashboard com sistema inteligente
     */
    public function render_dashboard_widget() {
        // Tenta buscar do cache primeiro
        $cached_content = get_transient($this->cache_key_html);

        if ($cached_content !== false) {
            echo '<!-- Dashboard carregado do cache -->';
            echo $cached_content;
            return;
        }

        // Busca o conteúdo
        $content = $this->get_dashboard_content();

        if (is_wp_error($content)) {
            echo '<div class="notice notice-error inline">';
            echo '<p><strong>Erro ao carregar o dashboard:</strong></p>';
            echo '<p>' . esc_html($content->get_error_message()) . '</p>';
            echo '<p><a href="' . admin_url('options-general.php?page=ml-custom-dashboard') . '">Verificar configurações</a></p>';
            echo '</div>';
            return;
        }

        // Salva no cache
        set_transient($this->cache_key_html, $content, $this->cache_time);

        echo $content;
    }

    /**
     * Busca conteúdo HTML do GitHub
     */
    private function get_dashboard_content() {
        $html_url = get_option($this->option_html_url, '');

        if (empty($html_url)) {
            return new WP_Error(
                'not_configured',
                'Dashboard não configurado. <a href="' . admin_url('options-general.php?page=ml-custom-dashboard') . '">Configure a URL do HTML aqui</a>.'
            );
        }

        // Busca HTML do GitHub
        $response = wp_remote_get($html_url, [
            'timeout' => 30,
            'sslverify' => true,
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml',
            ]
        ]);

        if (is_wp_error($response)) {
            return new WP_Error(
                'fetch_error',
                'Erro ao buscar HTML: ' . $response->get_error_message()
            );
        }

        $http_code = wp_remote_retrieve_response_code($response);

        if ($http_code !== 200) {
            return new WP_Error(
                'http_error',
                'Erro HTTP ' . $http_code . ' ao acessar: ' . esc_html($html_url) . '. Verifique se a URL está correta e é do tipo "raw".'
            );
        }

        $content = wp_remote_retrieve_body($response);

        if (empty($content)) {
            return new WP_Error('empty_content', 'HTML vazio retornado do GitHub.');
        }

        return '<div class="ml-custom-dashboard-content"><!-- Carregado do GitHub -->' . $content . '</div>';
    }

    /**
     * Injeta CSS customizado no dashboard
     */
    public function inject_custom_css() {
        $css_url = get_option($this->option_css_url, '');

        if (empty($css_url)) {
            return;
        }

        // Tenta buscar do cache
        $cached_css = get_transient($this->cache_key_css);

        if ($cached_css !== false) {
            echo '<!-- CSS customizado carregado do cache -->';
            echo '<style type="text/css">' . $cached_css . '</style>';
            return;
        }

        // Busca CSS da URL
        $response = wp_remote_get($css_url, [
            'timeout' => 30,
            'sslverify' => true,
            'headers' => [
                'Accept' => 'text/css',
            ]
        ]);

        if (is_wp_error($response)) {
            return; // Falha silenciosa para não quebrar o dashboard
        }

        $http_code = wp_remote_retrieve_response_code($response);

        if ($http_code === 200) {
            $css_content = wp_remote_retrieve_body($response);

            if (!empty($css_content)) {
                // Salva no cache
                set_transient($this->cache_key_css, $css_content, $this->cache_time);

                echo '<!-- CSS customizado carregado da URL -->';
                echo '<style type="text/css">' . $css_content . '</style>';
            }
        }
    }

    /**
     * Limpa o cache do HTML
     */
    public function clear_cache() {
        delete_transient($this->cache_key_html);
    }

    /**
     * Limpa o cache do CSS
     */
    public function clear_cache_css() {
        delete_transient($this->cache_key_css);
    }
    
    /**
     * Estilos customizados
     */
    public function custom_styles() {
        ?>
        <style type="text/css">
            #dashboard-widgets-wrap { padding-top: 0; }
            #ml_custom_dashboard { width: 100%; }
            #ml_custom_dashboard .inside { margin: 0; padding: 0; }
            #ml_custom_dashboard .postbox-header { display: none; }
            #dashboard-widgets .postbox-container { width: 100% !important; }
            #dashboard-widgets #postbox-container-2,
            #dashboard-widgets #postbox-container-3,
            #dashboard-widgets #postbox-container-4 { display: none; }
            #ml_custom_dashboard.postbox { border: none; box-shadow: none; background: transparent; }
            #screen-meta-links { display: none; }
            .drag-drop-message { display: none !important; }
            .ml-custom-dashboard-content { width: 100%; }
            .ml-custom-dashboard-content .notice { margin: 0; }
        </style>
        <?php
    }
}

// Inicializa o plugin
new ML_Custom_Dashboard_GitHub();