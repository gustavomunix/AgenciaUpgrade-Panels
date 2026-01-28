# 🚀 Guia Rápido - Dashboard GitHub Otimizado

Sistema ultra-leve para WordPress com cache de 12 horas.

## 📦 Arquivos Principais

```
PluginWP/
└── dashboard-github-optimized.php  ← Plugin WordPress (use este!)

PanelWooCommerce-Standalone.html    ← Painel pronto para GitHub (15KB)
```

## ⚡ Setup em 5 Minutos

### 1. Instalar Plugin WordPress

**Opção A: Via Code Snippets (Recomendado)**
```php
// 1. Instale o plugin "Code Snippets"
// 2. Crie novo snippet
// 3. Cole o conteúdo de dashboard-github-optimized.php
// 4. Ative
```

**Opção B: Como Plugin**
```bash
# 1. Copie dashboard-github-optimized.php para:
wp-content/plugins/dashboard-github/dashboard-github.php

# 2. Ative em Plugins > Plugins Instalados
```

### 2. Upload no GitHub

```bash
# Crie um repo (público ou privado)
git init
git add PanelWooCommerce-Standalone.html
git commit -m "Dashboard inicial"
git push
```

### 3. Obter URL Raw

No GitHub:
1. Clique no arquivo
2. Botão **"Raw"** (canto superior direito)
3. Copie a URL

```
✅ https://raw.githubusercontent.com/usuario/repo/main/PanelWooCommerce-Standalone.html
❌ https://github.com/usuario/repo/blob/main/PanelWooCommerce-Standalone.html
```

### 4. Configurar WordPress

1. **Configurações > Dashboard GitHub**
2. Cole a URL raw
3. CSS Adicional: deixe vazio (o HTML é standalone)
4. **Salvar**
5. Acesse [/wp-admin/](http://localhost/wp-admin/)

## 🎨 Personalizar por Cliente

Edite as variáveis CSS no HTML (linha 14):

```css
:root {
    --primary: #8338ec;        /* ← Cor primária do cliente */
    --secondary: #ffbe0b;      /* ← Cor secundária */
    --base: #4d4d66;           /* ← Cor de texto */
    /* ... */
}
```

**Workflow:**
```bash
# 1. Crie branch por cliente
git checkout -b cliente-abc

# 2. Edite cores no HTML
# 3. Commit
git commit -m "Cores Cliente ABC"
git push

# 4. Use URL raw dessa branch no WordPress
https://raw.githubusercontent.com/user/repo/cliente-abc/painel.html
```

## ⚡ Performance

### Cache Inteligente de 12 Horas

| Situação | Tempo | Requisições GitHub |
|----------|-------|-------------------|
| 1ª carga | ~200ms | 1 |
| Com cache (12h) | ~2ms | 0 |
| 100 acessos/dia | ~2ms | 2 total |

**Economia: 98% menos requisições ao GitHub!**

### Tamanho do Arquivo

```
HTML Standalone: ~15 KB
Com cache: ~15 KB no banco WordPress
Banda GitHub/dia: ~30 KB (2 downloads)
```

## 🔄 Atualizar Dashboard

Após editar no GitHub:

1. **WordPress > Configurações > Dashboard GitHub**
2. Botão: **"Limpar Cache Agora"**
3. Recarregue o dashboard

Ou espere 12h para atualização automática.

## 🎯 CSS Adicional (Opcional)

Se quiser usar CSS do Bricks:

```
CSS Adicional: https://seusite.com/wp-content/uploads/bricks/css/color-palettes.min.css
```

O HTML continuará funcionando standalone, mas também usará as variáveis do Bricks se disponíveis.

## ⚠️ Segurança - Repo Público

### ❌ NÃO Inclua:
- Senhas ou tokens
- Chaves API
- URLs de staging/dev
- Dados pessoais (LGPD)
- Informações confidenciais do cliente

### ✅ OK Incluir:
- Links para páginas públicas (`/wp-admin/...`)
- Ícones de CDN
- Estilos CSS
- HTML estrutural

## 🐛 Troubleshooting

### Dashboard não aparece

```bash
# 1. Teste a URL raw no navegador
curl https://raw.githubusercontent.com/.../painel.html

# 2. Verifique se é URL "raw" (não "blob")
# 3. Limpe o cache no WordPress
# 4. Veja erros em: Configurações > Dashboard GitHub
```

### Erro 404 GitHub

```bash
# Repositório privado? Precisa ser público OU
# Use GitHub token (não recomendado para segurança)

# Melhor: Torne o repo público
# (não tem problema, não há dados sensíveis!)
```

### Cache não atualiza

```bash
# Opção 1: Limpe manualmente
WordPress > Dashboard GitHub > Limpar Cache

# Opção 2: Salve as configurações novamente
# (salvar já limpa o cache automaticamente)
```

## 💡 Casos de Uso

### Agência - Múltiplos Clientes

```bash
meus-paineis/
├── cliente-a.html
├── cliente-b.html
└── cliente-c.html

# Ou use branches:
main
├── cliente-a/
├── cliente-b/
└── cliente-c/
```

### Freelancer - Vários Sites

```bash
# Um repo, vários arquivos
paineis/
├── woocommerce-simples.html
├── woocommerce-completo.html
└── servicos.html

# Cole URL diferente em cada site
```

### White Label / SaaS

```bash
# Template base + variações
templates/
├── base.html              # Template base
├── theme-blue.html        # Variação azul
├── theme-red.html         # Variação vermelha
└── theme-green.html       # Variação verde
```

## 📊 Comparação com Sistema Anterior

| Feature | Antes | Agora |
|---------|-------|-------|
| Requisições/dia | ~100 | ~2 |
| Tamanho cache | Sem cache | 12h |
| Configuração | Complexa | 1 campo |
| Manutenção | Upload FTP | Git push |
| Versionamento | Manual | Automático |
| Rollback | Difícil | Git checkout |

## 🎓 Dicas Pro

### 1. Use Tags para Versões

```bash
git tag -a v1.0 -m "Versão estável"
git push --tags

# URL: .../repo/v1.0/painel.html
# Garante versão específica
```

### 2. Teste Antes de Aplicar

```bash
# Abra a URL raw no navegador
# Veja se carrega corretamente
# Depois configure no WordPress
```

### 3. Monitore o Cache

```bash
# Status do cache visível em:
Configurações > Dashboard GitHub

# Mostra:
# - Tamanho do cache
# - Status (ativo/vazio)
# - Próxima atualização
```

### 4. GitHub Pages (Alternativa)

```bash
# Se quiser URL mais bonita:
# 1. Ative GitHub Pages no repo
# 2. Use: https://usuario.github.io/repo/painel.html
# 3. Ainda funciona com cache!
```

## 📈 Próximos Passos

1. **Personalize o HTML** com cores do cliente
2. **Faça upload no GitHub**
3. **Configure no WordPress**
4. **Teste o cache** (veja no settings)
5. **Deploy em outros clientes** (só muda URL!)

## 🆘 Suporte

Problemas? Verifique:

1. ✅ URL é "raw" do GitHub?
2. ✅ Repositório é público?
3. ✅ HTML carrega no navegador?
4. ✅ Cache foi limpo?
5. ✅ Plugin está ativado?

---

**Sistema otimizado para mínimo impacto no servidor! 🚀**

Cache de 12h = Máximo 2 requisições ao GitHub por dia
