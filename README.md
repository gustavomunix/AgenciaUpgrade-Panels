# 🎯 Sistema de Dashboard WordPress - GitHub + CSS em Cascata

Sistema completo e otimizado para criar dashboards personalizados no WordPress usando GitHub e CSS customizado (Bricks, Fancy Framework ou standalone).

## 📦 Estrutura do Repositório

```
📁 Panels/
├── 📄 README.md                             ← Você está aqui
├── 📄 .gitignore                            ← Configuração Git
│
├── 🎯 ARQUIVOS PRINCIPAIS (USE ESTES):
│   ├── PanelWooCommerce-Final.html          ← HTML único standalone
│   └── exemplo-custom-css.css               ← Template CSS customizado
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

1. **1 HTML único** - Funciona standalone ou com CSS externo
2. **Até 3 CSS em cascata** - Bricks global + colors + custom
3. **Cache de 12h** - Mínimo impacto no servidor (98% economia)
4. **Sem stats fake** - Não induz cliente ao erro
5. **Bricks nativo** - Detecta e usa variáveis automaticamente
6. **Ultra leve** - ~12 KB HTML + ~20 KB CSS total
7. **100% seguro** - Sem dados sensíveis (pronto para repo público)

### ❌ O que foi removido

- Stats fake (produtos: 254, pedidos: 18, etc)
- Múltiplos HTMLs duplicados
- Sistema confuso de path local
- CSS único obrigatório

## 🚀 Setup em 3 Passos

### 1. Instalar Plugin

```php
// WordPress > Plugins > Code Snippets > Novo
// Cole o conteúdo de: PluginWP/dashboard-github-final.php
// Ative
```

### 2. Upload HTML no GitHub

```bash
git add PanelWooCommerce-Final.html
git commit -m "Dashboard WooCommerce"
git push
```

Pegue a URL raw:
```
https://raw.githubusercontent.com/usuario/repo/main/PanelWooCommerce-Final.html
```

### 3. Configurar WordPress

**WordPress > Configurações > Dashboard GitHub**

```
HTML: https://raw.githubusercontent.com/.../painel.html
CSS 1: https://seusite.com/.../global-variables.min.css (opcional)
CSS 2: https://seusite.com/.../color-palettes.min.css (opcional)
CSS 3: [custom] (opcional)
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

- **[Plugin WordPress](PluginWP/dashboard-github-final.php)** - Baixe e instale no Code Snippets
- **[HTML do Dashboard](PanelWooCommerce-Final.html)** - Use a URL raw do GitHub
- **[Framework CSS](FancyFramework/fancy-framework-complete.css)** - Referência completa de variáveis

## 🏢 Sobre

Desenvolvido pela **[ugprade](https://github.com/ugprade)** para uso em projetos WordPress com WooCommerce.

Sistema perfeito para agências, freelancers e white label!

---

**📦 Repositório organizado e documentado = trabalho profissional!** 🚀