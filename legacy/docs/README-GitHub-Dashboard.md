# Dashboard Personalizado WordPress - GitHub Edition

Sistema completo para criar painéis customizados no WordPress usando GitHub e CSS com variáveis personalizadas por cliente.

## 📦 Arquivos do Sistema

### Plugin WordPress
- `PluginWP/snippet.php` - Plugin WordPress para carregar dashboard do GitHub

### Painéis HTML
- `PanelWooCommerce-GitHub.html` - Painel WooCommerce otimizado para GitHub
- `PanelWooCommerce-Fancy.html` - Painel standalone com Fancy Framework

### Framework CSS
- `FancyFramework/fancy-framework-complete.css` - CSS completo com todas as variáveis
- `FancyFramework/fancy-variables.json` - Variáveis em JSON
- `FancyFramework/fancy-color-palette.json` - Paleta de cores

## 🚀 Como Funciona

```
┌─────────────────────────────────────────────────────────┐
│ WordPress Dashboard                                     │
│  ↓                                                       │
│ Plugin carrega HTML do GitHub (raw URL)                 │
│  ↓                                                       │
│ Plugin injeta CSS com variáveis (Bricks ou Fancy)       │
│  ↓                                                       │
│ Cache de 30 minutos para performance                    │
└─────────────────────────────────────────────────────────┘
```

## 📋 Setup Completo

### 1. Instalar o Plugin WordPress

1. Copie o conteúdo de `PluginWP/snippet.php`
2. No WordPress, vá em **Plugins > Adicionar Novo > Upload**
3. Ou adicione via Code Snippets
4. Ative o plugin

### 2. Preparar o Repositório GitHub

```bash
# Crie um repositório (pode ser privado ou público)
# Estrutura recomendada:

seu-repo/
├── clientes/
│   ├── cliente-abc/
│   │   ├── painel-woocommerce.html
│   │   └── fancy-variables.css
│   ├── cliente-xyz/
│   │   ├── painel-custom.html
│   │   └── fancy-variables.css
└── README.md
```

### 3. Upload dos Arquivos

1. Faça upload do `PanelWooCommerce-GitHub.html` no seu repositório
2. Faça upload do `fancy-framework-complete.css` (ou use do Bricks)

### 4. Obter URLs Raw

**Para HTML:**
```
https://raw.githubusercontent.com/usuario/repo/main/PanelWooCommerce-GitHub.html
```

**Para CSS:**
```
# Opção 1: Fancy Framework no GitHub
https://raw.githubusercontent.com/usuario/repo/main/fancy-framework-complete.css

# Opção 2: Bricks Color Palettes (do próprio site)
https://seusite.com/wp-content/uploads/bricks/css/color-palettes.min.css
```

### 5. Configurar no WordPress

1. Vá em **Configurações > Dashboard Personalizado**
2. Cole a URL raw do HTML no campo **"URL do HTML (GitHub Raw)"**
3. Cole a URL do CSS no campo **"URL do CSS com Variáveis"**
4. Clique em **Salvar Configurações**
5. Acesse o Dashboard: [/wp-admin/](http://localhost/wp-admin/)

## 🎨 Personalização por Cliente

### Opção 1: GitHub (Recomendado)

Crie um repositório por cliente ou use branches:

```bash
# Um repo por cliente
cliente-abc-dashboard/
├── painel.html
└── variaveis.css

# Ou branches no mesmo repo
main
├── cliente-abc/
└── cliente-xyz/
```

### Opção 2: Bricks Color Palettes

Use as variáveis do próprio Bricks que já estão no site do cliente:

```
https://cliente.com/wp-content/uploads/bricks/css/color-palettes.min.css
```

### Opção 3: Fancy Framework Customizado

Edite o `fancy-framework-complete.css` para cada cliente:

```css
:root {
  /* Cores personalizadas do Cliente ABC */
  --primary: #ff6b6b;
  --secondary: #4ecdc4;
  --base: #2d3436;
  /* ... */
}
```

## 🎯 Variáveis CSS Disponíveis

O painel usa variáveis CSS que podem vir do Fancy Framework ou Bricks:

### Cores
```css
--primary, --primary-dark, --primary-light
--secondary, --secondary-dark, --secondary-light
--base, --base-dark, --base-light
--white, --black
```

### Espaçamento
```css
--space-xs, --space-s, --space-m, --space-l, --space-xl, --space-xxl
```

### Tipografia
```css
--h1, --h2, --h3, --h4, --h5, --h6
--text-xs, --text-s, --text-m, --text-l, --text-xl
--heading-color, --text-color
--heading-font-weight, --text-font-weight
```

### Radius
```css
--radius-s, --radius-m, --radius-l, --radius-xl
--radius-pill, --radius-circle
```

## ⚡ Performance

- **Cache de 30 minutos**: HTML e CSS são cacheados
- **GitHub CDN**: Usa a CDN do GitHub para entregar arquivos
- **Carregamento assíncrono**: Não bloqueia o carregamento do WordPress
- **Fallback gracioso**: Se falhar, mostra mensagem amigável

### Limpar Cache

Após fazer alterações no GitHub:

1. Vá em **Configurações > Dashboard Personalizado**
2. Clique em **Limpar Cache do Dashboard**
3. Recarregue o Dashboard

## 🔧 Troubleshooting

### Dashboard não aparece

1. Verifique se a URL do GitHub está correta (use **raw**)
2. Teste a URL diretamente no navegador
3. Limpe o cache do plugin
4. Verifique se o repositório é público ou tem permissões corretas

### CSS não está sendo aplicado

1. Verifique a URL do CSS no campo de configurações
2. Teste a URL do CSS diretamente no navegador
3. Limpe o cache
4. Inspecione a página e veja se as variáveis CSS estão sendo carregadas

### Erro 404 no GitHub

Use a URL **raw**, não a URL normal do arquivo:

❌ Errado:
```
https://github.com/usuario/repo/blob/main/painel.html
```

✅ Correto:
```
https://raw.githubusercontent.com/usuario/repo/main/painel.html
```

## 💡 Casos de Uso

### Agência com Múltiplos Clientes

```
minha-agencia-dashboards/
├── cliente-a/
│   ├── painel-woocommerce.html
│   └── cores-cliente-a.css
├── cliente-b/
│   ├── painel-custom.html
│   └── cores-cliente-b.css
└── cliente-c/
    ├── painel-simples.html
    └── cores-cliente-c.css
```

**Benefícios:**
- Um único repositório para todos os clientes
- Fácil versionamento e backup
- Deploy instantâneo mudando apenas a URL
- Histórico de alterações pelo Git

### Freelancer

Use branches para versões diferentes:

```bash
git checkout -b versao-minimalista
git checkout -b versao-completa
git checkout -b versao-ecommerce
```

### SaaS / White Label

Crie templates reutilizáveis:

```
templates/
├── base/
│   └── painel-base.html
├── woocommerce/
│   └── painel-woo.html
└── servicos/
    └── painel-servicos.html
```

## 🎓 Dicas Profissionais

1. **Versionamento**: Use tags no Git para versões estáveis
   ```bash
   git tag -a v1.0 -m "Versão 1.0 - Cliente ABC"
   ```

2. **Documentação**: Mantenha um README por cliente
   ```markdown
   # Cliente ABC - Dashboard
   - Primary Color: #8338ec
   - Secondary Color: #ffbe0b
   - Última atualização: 2024-01-28
   ```

3. **Testes**: Crie um ambiente de staging no GitHub Pages
4. **Backup**: O GitHub já é o backup! Mas clone localmente também
5. **CI/CD**: Use GitHub Actions para validar HTML/CSS antes do commit

## 📄 Licença

Este sistema é fornecido como está. Use livremente em seus projetos!

---

**Desenvolvido para WordPress com amor e café ☕**
