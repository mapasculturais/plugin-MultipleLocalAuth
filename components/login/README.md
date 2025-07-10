# Componente `<login>`
Componente para efetuar login seguro no mapas culturais BaseV2

O componente suporta dois modos de operação:
- Modo padrão: login tradicional com campos de identificação e senha na mesma tela
- Modo wizard: fluxo guiado em etapas para melhor experiência do usuário

No fluxo guiado (wizard), as solicitações são divididas em duas etapas:
- No primeiro momento, a pessoa informa apenas a identificação e o sistema verifica em qual cenário a pessoa se enquadra;
- No segundo momento, o sistema conduz a pessoa conforme o cenário:
  - Se a pessoa não tem conta, o sistema conduz a pessoa a um novo cadastro;
  - Se a pessoa já tem conta, mas a senha não está definida (ex: primeiro acesso em determinada versão), então o sistema conduz a pessoa à geração de uma nova senha;
  - Se a pessoa já tem conta e tem senha definida, então o sistema solicita que a pessoa informe a senha.
  
## Propriedades
- *String **config*** - json com configurações necessárias para o funcionamento do login (definidas no php)

### Ativando o modo wizard
Para ativar o fluxo guiado, é preciso definir nas configurações uma variável de nome ```wizard``` e o valor ```true```. Sem fazer isso, ou seja, sem definir a variável ou se a variável contiver outro valor, o comportamento segue o padrão clássico com identificação e senha na mesma tela.

| Código sem ativação (acesso clássico) | Código com ativação (acesso guiado) |
|--|--|
| <pre>...<br>    'auth.config' => [<br>        'salt' => env('AUTH_SALT', 'SECURITY_SALT'),<br>        'timeout' => '24 hours',<br>...</pre> |  <pre>...<br>    'auth.config' => [<br>        'salt' => env('AUTH_SALT', 'SECURITY_SALT'),<br>        **'wizard' => 'true',**<br>        'timeout' => '24 hours',<br>...</pre>  |

### Importando componente
```PHP
<?php 
$this->import('login');
?>
```

### Exemplos de uso
```HTML
<!-- utilizaçao básica -->
<login config='<?= $configs; ?>'></login>
```