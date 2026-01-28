<?php
/**
 * Plugin Name: Dashboard GitHub - Otimizado
 * Description: Dashboard customizado do GitHub com cache inteligente de 12h
 * Version: 5.0
 * Author: Gustavo Muniz
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

class ML_Dashboard_GitHub_Optimized {

    private $option_html_url = 'ml_dashboard_html_url';
    private $option_css_url = 'ml_dashboard_css_url';
    private $cache_key_html = 'ml_dashboard_html_cache_v2';
    private $cache_key_css = 'ml_dashboard_css_cache_v2';
    private $cache_time = 43200; // 12 horas - reduz requisições ao GitHub

    public function __construct() {
        // Configurações
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);

        // Dashboard
        add_action('wp_dashboard_setup', [$this, 'setup_dashboard'], 999);
        add_filter('get_user_option_meta-box-order_dashboard', '__return_empty_array');
        add_filter('get_user_option_metaboxhidden_dashboard', '__return_empty_array');

        // CSS opcional
        add_action('admin_head-index.php', [$this, 'inject_custom_css']);
        add_action('admin_head-index.php', [$this, 'custom_styles']);

        // Limpa cache ao salvar
        add_action('update_option_' . $this->option_html_url, [$this, 'clear_all_cache']);
        add_action('update_option_' . $this->option_css_url, [$this, 'clear_all_cache']);
    }

    /**
     * Adiciona página de configurações
     */
    public function add_settings_page() {
        add_options_page(
            'Dashboard GitHub',
            'Dashboard GitHub',
            'manage_options',
            'ml-dashboard-github',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Registra configurações
     */
    public function register_settings() {
        register_setting('ml_dashboard_settings', $this->option_html_url, [
            'type' => 'string',
            'sanitize_callback' => [$this, 'sanitize_github_url'],
            'default' => ''
        ]);

        register_setting('ml_dashboard_settings', $this->option_css_url, [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ]);

        add_settings_section(
            'ml_dashboard_main',
            'Configuração do Dashboard GitHub',
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
            'ml_dashboard_css_url_field',
            'URL do CSS Adicional (Opcional)',
            [$this, 'css_url_field_callback'],
            'ml-dashboard-github',
            'ml_dashboard_main'
        );
    }

    /**
     * Sanitiza e valida URL do GitHub
     */
    public function sanitize_github_url($input) {
        $input = trim($input);

        if (empty($input)) {
            return '';
        }

        // Valida se é URL
        if (!filter_var($input, FILTER_VALIDATE_URL)) {
            add_settings_error(
                $this->option_html_url,
                'invalid_url',
                'URL inválida. Use uma URL válida do GitHub.',
                'error'
            );
            return get_option($this->option_html_url);
        }

        // Recomenda raw.githubusercontent.com
        if (strpos($input, 'raw.githubusercontent.com') === false) {
            add_settings_error(
                $this->option_html_url,
                'not_raw',
                'Recomendado: Use a URL "raw" do GitHub para melhor performance (raw.githubusercontent.com)',
                'warning'
            );
        }

        return esc_url_raw($input);
    }

    /**
     * Seção de configuração
     */
    public function settings_section_callback() {
        ?>
        <p>Configure a URL do GitHub para carregar seu dashboard personalizado. <strong>Cache de 12 horas</strong> para otimização.</p>
        <div class="notice notice-info inline">
            <p><strong>💡 Dica:</strong> O HTML deve ser standalone (todos os estilos e scripts inline ou via CDN).</p>
        </div>
        <?php
    }

    /**
     * Campo URL do HTML
     */
    public function html_url_field_callback() {
        $value = get_option($this->option_html_url, '');
        ?>
        <input type="url"
               name="<?php echo esc_attr($this->option_html_url); ?>"
               value="<?php echo esc_attr($value); ?>"
               class="large-text"
               placeholder="https://raw.githubusercontent.com/usuario/repo/main/dashboard.html"
               required />
        <p class="description">
            <strong>URL raw do GitHub</strong> - O arquivo será carregado uma vez a cada 12 horas.<br>
            <strong>Exemplo:</strong> <code>https://raw.githubusercontent.com/usuario/repo/main/painel.html</code>
        </p>
        <?php
        if (!empty($value)) {
            $cached = get_transient($this->cache_key_html);
            if ($cached !== false) {
                $cache_size = strlen($cached);
                $cache_size_kb = number_format($cache_size / 1024, 2);
                echo '<p class="description" style="color: #46b450; font-weight: 600;">';
                echo '✓ Cache ativo (' . $cache_size_kb . ' KB) - Próxima atualização em até 12h';
                echo '</p>';
            } else {
                echo '<p class="description" style="color: #f0a000;">';
                echo '⚠ Cache vazio - Será carregado na próxima visita ao dashboard';
                echo '</p>';
            }
        }
        ?>
        <?php
    }

    /**
     * Campo URL do CSS
     */
    public function css_url_field_callback() {
        $value = get_option($this->option_css_url, '');
        ?>
        <input type="url"
               name="<?php echo esc_attr($this->option_css_url); ?>"
               value="<?php echo esc_attr($value); ?>"
               class="large-text"
               placeholder="https://seusite.com/wp-content/uploads/bricks/css/variables.css" />
        <p class="description">
            <strong>Opcional</strong> - CSS com variáveis de branding do cliente (ex: Bricks color palettes).<br>
            Se deixar vazio, use estilos inline no HTML ou links para CDN.<br>
            <strong>Exemplo:</strong> <code><?php echo home_url('/wp-content/uploads/bricks/css/color-palettes.min.css'); ?></code>
        </p>
        <?php
    }

    /**
     * Página de configurações
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        settings_errors();

        $html_url = get_option($this->option_html_url, '');
        $css_url = get_option($this->option_css_url, '');
        $cache_html = get_transient($this->cache_key_html);
        $cache_css = get_transient($this->cache_key_css);

        ?>
        <div class="wrap">
            <h1>⚡ Dashboard GitHub - Otimizado</h1>

            <?php if (!empty($html_url)): ?>
                <div class="notice notice-success inline">
                    <p>
                        <strong>✓ Dashboard configurado!</strong>
                        Cache: <?php echo $cache_html !== false ? '✓ Ativo' : '○ Inativo'; ?>
                        | CSS: <?php echo !empty($css_url) ? '✓ Configurado' : '○ Opcional'; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="notice notice-warning inline">
                    <p><strong>⚠ Configure a URL do HTML do GitHub para começar.</strong></p>
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
                <h2>🚀 Sistema de Cache Inteligente</h2>
                <p>Este plugin foi otimizado para <strong>mínimo impacto no servidor</strong>:</p>

                <h3>📊 Como funciona:</h3>
                <ol>
                    <li><strong>Primeira visita:</strong> Baixa HTML do GitHub (~100-500ms)</li>
                    <li><strong>Armazena em cache:</strong> Salva no banco do WordPress por 12 horas</li>
                    <li><strong>Visitas subsequentes:</strong> Lê do cache local (~1-5ms)</li>
                    <li><strong>Após 12h:</strong> Atualiza automaticamente do GitHub</li>
                </ol>

                <h3>⚡ Performance:</h3>
                <table class="widefat" style="max-width: 600px;">
                    <thead>
                        <tr>
                            <th>Situação</th>
                            <th>Tempo</th>
                            <th>Requisições GitHub</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Primeira carga</td>
                            <td>~100-500ms</td>
                            <td style="color: #f0a000;">1 requisição</td>
                        </tr>
                        <tr>
                            <td>Com cache (12h)</td>
                            <td>~1-5ms</td>
                            <td style="color: #46b450;">0 requisições</td>
                        </tr>
                        <tr>
                            <td>Atualização (após 12h)</td>
                            <td>~100-500ms</td>
                            <td style="color: #f0a000;">1 requisição</td>
                        </tr>
                    </tbody>
                </table>

                <h3>💡 Estimativa de Requisições:</h3>
                <ul>
                    <li>Com 12h de cache = <strong>máximo 2 requisições por dia</strong></li>
                    <li>100 acessos ao dashboard por dia = <strong>apenas 2 requisições ao GitHub</strong></li>
                    <li>Economia: <strong>98% menos requisições</strong></li>
                </ul>
            </div>

            <div class="card">
                <h2>📋 Guia Rápido</h2>

                <h3>1. Prepare seu HTML:</h3>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
&lt;!-- dashboard.html --&gt;
&lt;style&gt;
  /* Todos os estilos inline OU */
&lt;/style&gt;
&lt;link href="https://cdn.jsdelivr.net/..." rel="stylesheet"&gt;

&lt;div class="meu-dashboard"&gt;
  &lt;!-- Seu conteúdo --&gt;
&lt;/div&gt;</pre>

                <h3>2. Faça upload no GitHub:</h3>
                <ol>
                    <li>Crie um repositório (público ou privado)</li>
                    <li>Adicione o arquivo <code>dashboard.html</code></li>
                    <li>Commit e push</li>
                </ol>

                <h3>3. Obtenha a URL raw:</h3>
                <p>No GitHub, clique no arquivo → Botão "Raw" → Copie a URL</p>
                <code>https://raw.githubusercontent.com/usuario/repo/main/dashboard.html</code>

                <h3>4. Configure aqui e pronto!</h3>
            </div>

            <div class="card">
                <h2>🎨 CSS Adicional (Opcional)</h2>
                <p>Você tem 3 opções para estilizar o dashboard:</p>

                <h3>Opção 1: Inline no HTML (Recomendado)</h3>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
&lt;style&gt;
  :root {
    --primary: #8338ec;
    --secondary: #ffbe0b;
  }
  .card { background: var(--primary); }
&lt;/style&gt;</pre>

                <h3>Opção 2: Link para CDN no HTML</h3>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
&lt;link href="https://cdn.jsdelivr.net/gh/usuario/repo@main/styles.css" rel="stylesheet"&gt;</pre>

                <h3>Opção 3: Campo CSS Adicional (acima)</h3>
                <p>Use para injetar CSS externo (ex: Bricks color palettes do próprio site)</p>
            </div>

            <?php if (current_user_can('manage_options')): ?>
            <div class="card">
                <h2>🔄 Gerenciar Cache</h2>

                <p><strong>Status do Cache:</strong></p>
                <ul>
                    <li>HTML: <?php echo $cache_html !== false ? '✓ Ativo (' . number_format(strlen($cache_html) / 1024, 2) . ' KB)' : '○ Vazio'; ?></li>
                    <li>CSS: <?php echo $cache_css !== false ? '✓ Ativo (' . number_format(strlen($cache_css) / 1024, 2) . ' KB)' : '○ Vazio'; ?></li>
                </ul>

                <p>Após fazer alterações no GitHub, limpe o cache para ver as mudanças imediatamente:</p>

                <form method="post" action="">
                    <?php wp_nonce_field('ml_clear_cache', 'ml_cache_nonce'); ?>
                    <button type="submit" name="ml_clear_cache" class="button button-secondary">Limpar Cache Agora</button>
                </form>

                <?php
                if (isset($_POST['ml_clear_cache']) &&
                    wp_verify_nonce($_POST['ml_cache_nonce'], 'ml_clear_cache')) {
                    $this->clear_all_cache();
                    echo '<div class="notice notice-success inline" style="margin-top: 10px;"><p>✓ Cache limpo! O dashboard será recarregado do GitHub na próxima visita.</p></div>';
                }
                ?>
            </div>
            <?php endif; ?>

            <div class="card">
                <h2>⚠️ Segurança - Repositório Público</h2>
                <p><strong>Não inclua no HTML:</strong></p>
                <ul style="color: #dc3232;">
                    <li>❌ Senhas, tokens ou chaves API</li>
                    <li>❌ Informações sensíveis do cliente</li>
                    <li>❌ URLs de ambientes de desenvolvimento/staging</li>
                    <li>❌ Dados pessoais (LGPD)</li>
                </ul>
                <p><strong>OK incluir:</strong></p>
                <ul style="color: #46b450;">
                    <li>✓ Links para páginas públicas do WordPress</li>
                    <li>✓ Ícones e imagens de CDN</li>
                    <li>✓ Estilos CSS</li>
                    <li>✓ Estrutura HTML do dashboard</li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Configura dashboard
     */
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

    /**
     * Renderiza dashboard
     */
    public function render_dashboard() {
        // Tenta cache primeiro
        $cached = get_transient($this->cache_key_html);

        if ($cached !== false) {
            echo '<!-- ⚡ Carregado do cache local -->';
            echo $cached;
            return;
        }

        // Busca do GitHub
        $content = $this->fetch_from_github();

        if (is_wp_error($content)) {
            echo '<div class="notice notice-error inline">';
            echo '<p><strong>Erro ao carregar dashboard:</strong></p>';
            echo '<p>' . esc_html($content->get_error_message()) . '</p>';
            echo '<p><a href="' . admin_url('options-general.php?page=ml-dashboard-github') . '" class="button">Verificar Configurações</a></p>';
            echo '</div>';
            return;
        }

        // Salva no cache (12h)
        set_transient($this->cache_key_html, $content, $this->cache_time);

        echo '<!-- 🌐 Carregado do GitHub e cacheado por 12h -->';
        echo $content;
    }

    /**
     * Busca HTML do GitHub
     */
    private function fetch_from_github() {
        $html_url = get_option($this->option_html_url, '');

        if (empty($html_url)) {
            return new WP_Error(
                'not_configured',
                'Dashboard não configurado. <a href="' . admin_url('options-general.php?page=ml-dashboard-github') . '">Configure aqui</a>.'
            );
        }

        $response = wp_remote_get($html_url, [
            'timeout' => 30,
            'sslverify' => true,
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml',
                'User-Agent' => 'WordPress/' . get_bloginfo('version')
            ]
        ]);

        if (is_wp_error($response)) {
            return new WP_Error(
                'fetch_error',
                'Erro ao buscar do GitHub: ' . $response->get_error_message()
            );
        }

        $http_code = wp_remote_retrieve_response_code($response);

        if ($http_code !== 200) {
            return new WP_Error(
                'http_error',
                sprintf('HTTP %d - Verifique se a URL está correta e é pública.', $http_code)
            );
        }

        $content = wp_remote_retrieve_body($response);

        if (empty($content)) {
            return new WP_Error('empty_content', 'HTML vazio retornado.');
        }

        return '<div class="ml-dashboard-content">' . $content . '</div>';
    }

    /**
     * Injeta CSS opcional
     */
    public function inject_custom_css() {
        $css_url = get_option($this->option_css_url, '');

        if (empty($css_url)) {
            return;
        }

        // Tenta cache
        $cached = get_transient($this->cache_key_css);

        if ($cached !== false) {
            echo '<!-- ⚡ CSS do cache -->';
            echo '<style type="text/css">' . $cached . '</style>';
            return;
        }

        // Busca CSS
        $response = wp_remote_get($css_url, [
            'timeout' => 15,
            'sslverify' => true,
            'headers' => ['Accept' => 'text/css']
        ]);

        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $css = wp_remote_retrieve_body($response);
            if (!empty($css)) {
                set_transient($this->cache_key_css, $css, $this->cache_time);
                echo '<!-- 🌐 CSS carregado e cacheado -->';
                echo '<style type="text/css">' . $css . '</style>';
            }
        }
    }

    /**
     * Estilos do widget
     */
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

    /**
     * Limpa todo o cache
     */
    public function clear_all_cache() {
        delete_transient($this->cache_key_html);
        delete_transient($this->cache_key_css);
    }
}

// Inicializa
new ML_Dashboard_GitHub_Optimized();
