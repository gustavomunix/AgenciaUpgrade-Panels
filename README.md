# 🎯 Sistema de Dashboard WordPress - GitHub + CSS em Cascata

Sistema completo e otimizado para criar dashboards personalizados no WordPress usando GitHub e CSS customizado (Bricks, Fancy Framework ou standalone).

## 📦 Estrutura do Repositório

```
📁 Panels/
├── 📄 README.md                             ← Você está aqui
├── 📄 .gitignore                            ← Configuração Git
├── 📄 exemplo-custom-css.css                ← Template CSS customizado
│
├── 📁 panels/                               ← PAINÉIS POR TIPO DE CLIENTE
│   ├── 📄 README.md                         ← Guia de painéis
│   ├── 📁 ecommerce/                        ← ✅ WooCommerce (pronto)
│   │   ├── PanelWooCommerce-Final.html      ← Lojas virtuais
│   │   └── README.md
│   ├── 📁 blog/                             ← 🚧 Sites de conteúdo (em breve)
│   │   └── README.md
│   ├── 📁 portfolio/                        ← 🚧 Profissionais/Agências (em breve)
│   │   └── README.md
│   └── 📁 base/                             ← 🚧 Genérico (em breve)
│       └── README.md
│
├── 📁 PluginWP/
│   └── dashboard-github-final.php           ← Plugin WordPress final
│
├── 📁 FancyFramework/
│   ├── fancy-framework-complete.css         ← Framework CSS completo
│   ├── fancy-variables.json                 ← Variáveis originais
│   ├── fancy-color-palette.json             ← Paleta de cores
│   └── framework.css                        ← Framework base
│
├── 📁 docs/                                 ← Documentação completa
│   ├── GUIA-SISTEMA-FINAL.md                ← Guia principal do sistema
│   ├── CUSTOMIZAR-CORES.md                  ← 6 paletas + customização
│   └── ARQUIVOS-USAR.md                     ← O que usar vs ignorar
│
└── 📁 legacy/                               ← Versões antigas (referência)
    ├── html/                                ← HTMLs antigos
    ├── plugin/                              ← Plugins antigos
    └── docs/                                ← Docs antigos
```

## ⚡ Principais Características

### ✅ O que tem de bom

1. **Painéis por tipo** - E-commerce, Blog, Portfolio, Base
2. **1 HTML por tipo** - Funciona standalone ou com CSS externo
3. **Até 3 CSS em cascata** - Bricks global + colors + custom
4. **Cache de 12h** - Mínimo impacto no servidor (98% economia)
5. **Sem stats fake** - Não induz cliente ao erro
6. **Bricks nativo** - Detecta e usa variáveis automaticamente
7. **Ultra leve** - ~12 KB HTML + ~20 KB CSS total
8. **100% seguro** - Sem dados sensíveis (pronto para repo público)

### 📊 Painéis Disponíveis

| Tipo | Status | Arquivo | Para quem |
|------|--------|---------|-----------|
| 📦 **E-commerce** | ✅ Pronto | `panels/ecommerce/` | Lojas WooCommerce |
| 📝 **Blog** | 🚧 Em breve | `panels/blog/` | Sites de conteúdo |
| 🎨 **Portfolio** | 🚧 Em breve | `panels/portfolio/` | Designers/Agências |
| 🔧 **Base** | 🚧 Em breve | `panels/base/` | Genérico |

**[Ver todos os painéis →](panels/)**

## 🚀 Setup em 3 Passos

### 1. Instalar Plugin

```php
// WordPress > Plugins > Code Snippets > Novo
// Cole o conteúdo de: PluginWP/dashboard-github-final.php
// Ative
```

### 2. Escolher Painel + URL GitHub

**Escolha o painel certo para o tipo de cliente:**

```bash
# E-commerce (WooCommerce)
https://raw.githubusercontent.com/ugprade/Panels/main/panels/ecommerce/PanelWooCommerce-Final.html

# Blog (em breve)
https://raw.githubusercontent.com/ugprade/Panels/main/panels/blog/PanelBlog.html

# Portfolio (em breve)
https://raw.githubusercontent.com/ugprade/Panels/main/panels/portfolio/PanelPortfolio.html

# Base genérico (em breve)
https://raw.githubusercontent.com/ugprade/Panels/main/panels/base/PanelBase.html
```

### 3. Configurar WordPress

**WordPress > Configurações > Dashboard GitHub**

```
HTML: https://raw.githubusercontent.com/ugprade/Panels/main/panels/[TIPO]/[ARQUIVO].html
CSS 1: https://seusite.com/.../global-variables.min.css (opcional)
CSS 2: https://seusite.com/.../color-palettes.min.css (opcional)
CSS 3: [custom] (opcional)
```

**Exemplo para loja WooCommerce:**
```
HTML: https://raw.githubusercontent.com/ugprade/Panels/main/panels/ecommerce/PanelWooCommerce-Final.html
CSS 1: https://loja.com/wp-content/uploads/bricks/css/global-variables.min.css
CSS 2: https://loja.com/wp-content/uploads/bricks/css/color-palettes.min.css
CSS 3: [vazio]
```

## 🎨 3 Modos de Uso

**Modo 1: Standalone** - HTML: ✓ | CSS: ○○○ → Roxo padrão
**Modo 2: Com Bricks** - HTML: ✓ | CSS: ✓✓○ → Cores do cliente
**Modo 3: Customizado** - HTML: ✓ | CSS: ✓✓✓ → Totalmente custom

## 📊 Performance

- Cache: 12 horas
- Requisições/dia: 8 (vs 100 sem cache)
- Tamanho total: ~26 KB
- Economia: 92%

## 📚 Documentação Completa

- **[GUIA-SISTEMA-FINAL.md](docs/GUIA-SISTEMA-FINAL.md)** - Guia completo do sistema (setup, casos de uso, troubleshooting)
- **[CUSTOMIZAR-CORES.md](docs/CUSTOMIZAR-CORES.md)** - 6 paletas de cores prontas + como customizar
- **[ARQUIVOS-USAR.md](docs/ARQUIVOS-USAR.md)** - O que usar vs o que ignorar (evite confusão!)
- **[exemplo-custom-css.css](exemplo-custom-css.css)** - Template CSS #3 com 8 opções de customização

## 🔗 Links Úteis

- **[Painéis Disponíveis](panels/)** - Veja todos os tipos de painel
- **[Plugin WordPress](PluginWP/dashboard-github-final.php)** - Baixe e instale no Code Snippets
- **[Painel E-commerce](panels/ecommerce/PanelWooCommerce-Final.html)** - HTML para WooCommerce
- **[Framework CSS](FancyFramework/fancy-framework-complete.css)** - Referência completa de variáveis
- **[Template CSS](exemplo-custom-css.css)** - Exemplo de customização CSS #3

## 🏢 Sobre

Desenvolvido pela **[ugprade](https://github.com/ugprade)** para uso em projetos WordPress com WooCommerce.

Sistema perfeito para agências, freelancers e white label!

---

**📦 Repositório organizado e documentado = trabalho profissional!** 🚀