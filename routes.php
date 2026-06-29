<?php

require_once __DIR__ . '/app/Middleware/auth.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';

function responderRotaNaoEncontrada($mensagem) {
    http_response_code(404);
    exit($mensagem);
}

$controller = $_GET['controller'] ?? 'auth';
$action     = $_GET['action']     ?? 'login';

if ($controller === 'auth') {
    $auth = new AuthController();
    switch ($action) {
        case 'login':     $auth->exibirLogin(); break;
        case 'entrar':    $auth->entrar();      break;
        case 'dashboard': exigirAutenticacao(); $auth->dashboard(); break;
        case 'logout':    $auth->logout();      break;
        default: responderRotaNaoEncontrada('Ação de autenticação não encontrada.');
    }
    exit;
}

exigirAutenticacao();

switch ($controller) {

    case 'frontend':
        require_once __DIR__ . '/app/Controllers/FrontendController.php';
        $frontend = new FrontendController();
        switch ($action) {
            case 'pessoas':       $frontend->pessoas();       break;
            case 'tipos':         $frontend->tipos();         break;
            case 'atendimentos':  $frontend->atendimentos();  break;
            default: responderRotaNaoEncontrada('Página não encontrada.');
        }
        break;

    case 'dashboard':
        require_once __DIR__ . '/app/Controllers/DashboardController.php';
        $dashboard = new DashboardController();
        switch ($action) {
            case 'resumo': $dashboard->resumo(); break;
            default: responderRotaNaoEncontrada('Ação de dashboard não encontrada.');
        }
        break;

    case 'pessoas':
        require_once __DIR__ . '/app/Controllers/PessoasController.php';
        $obj = new PessoasController();
        switch ($action) {
            case 'listar':    $obj->listar();    break;
            case 'buscar':    $obj->buscar();    break;
            case 'criar':     $obj->criar();     break;
            case 'atualizar': $obj->atualizar(); break;
            case 'inativar':  $obj->inativar();  break;
            default: responderRotaNaoEncontrada('Ação de pessoas não encontrada.');
        }
        break;

    case 'tipos':
        require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
        $obj = new TiposAtendimentosController();
        switch ($action) {
            case 'listar':      $obj->listar();      break;
            case 'buscar':
            case 'buscarPorId': $obj->buscar();      break;
            case 'criar':       $obj->criar();       break;
            case 'atualizar':   $obj->atualizar();   break;
            case 'inativar':    $obj->inativar();    break;
            default: responderRotaNaoEncontrada('Ação de tipos de atendimento não encontrada.');
        }
        break;

    case 'atendimentos':
        require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
        $obj = new AtendimentosController();
        switch ($action) {
            case 'listar':        $obj->listar();        break;
            case 'buscar':
            case 'visualizar':    $obj->visualizar();    break;
            case 'criar':         $obj->criar();         break;
            case 'alterarStatus':
            case 'atualizarStatus':
                $obj->alterarStatus();
                break;
            default: responderRotaNaoEncontrada('Ação de atendimentos não encontrada.');
        }
        break;

    default:
        responderRotaNaoEncontrada('Controller não encontrado.');
}
