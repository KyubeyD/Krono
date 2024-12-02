<?php 
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 'home';

    switch ($page) {
        case 'home':
            require_once 'PAGES/home.php';
            break;
        case 'login':
            header('location: CREDENTIALS/login.php');
            break;
        case 'cadastro':
            header('location: CREDENTIALS/cadastro.php');
            break;
        case 'dashboard':
            require_once 'PAGES/dashboard.php';
            break;
        case 'users':
            require_once 'PAGES/USUARIO/listaUsu.php';
            break;
        case 'disciplinas':
            require_once 'PAGES/DISCIPLINA/listaDisc.php';
            break;
        case 'turmas':
            require_once 'PAGES/TURMA/listaTurma.php';
            break;
        case 'cursos':
            require_once 'PAGES/CURSO/listaCurso.php';
            break;
        case 'professores':
            require_once 'PAGES/PROFESSOR/listaProf.php';
            break;
        case 'escolas':
            require_once 'PAGES/ESCOLA/listaEsc.php';
            break;
        case 'meu_horario_escola':
            require_once 'PAGES/GRADE/minhaGradeEsc.php';
            break;
        case 'disponibilidade_professor':
            require_once 'PAGES/DISPONIBILIDADE/dispoListaEsc.php';
            break;
        case 'minha_disponibilidade':
            require_once 'PAGES/DISPONIBILIDADE/dispoProf.php';
            break;
        case 'orientacoes':
            require_once 'PAGES/DISPONIBILIDADE/orientacoes.php';
            break;
        case 'escolha_carga_horaria':
            require_once 'PAGES/DISPONIBILIDADE/chooseCargaHoraria.php';
            break;
        case 'grades_horarias':
            require_once 'PAGES/GRADE/gradesTurmas.php';
            break;
        case 'view_grade':
            require_once 'PAGES/GRADE/viewGradeTurma.php';
            break;
        case 'fazer_grade':
            require_once 'PAGES/GRADE/makeGrade.php';
            break;
        case 'perfil':
            require_once 'PAGES/PROFESSOR/perfilProfessor.php';
            break;
        case 'minhasOrientacoes':
            require_once 'PAGES/REGRA/listaRegra.php';
            break;
        case 'adicionar_regra':
            require_once 'PAGES/REGRA/faddRegra.php';
            break;
        case 'relatorioProfessor':
            require_once 'PAGES/PROFESSOR/rel_prof.php';
            break;
        case 'relatorioCurso':
            require_once 'PAGES/CURSO/relatorioCurso.php';
            break;
        case 'relatorioDisciplina':
            require_once 'PAGES/DISCIPLINA/relatorioDisciplina.php';
            break;
        case 'relatorioTurma':
            require_once 'PAGES/TURMA/relatorioTurma.php';
            break;
        case 'relatorioGradeHoraria':
            require_once 'PAGES/GRADE/relatorioGradeHoraria.php';
            break;
        case 'notificacao':
            require_once 'PAGES/notificacao.php';
            break;
        case 'logout':
            header('location: CREDENTIALS/logout.php');
            break;
        default:
            require_once 'PAGES/home.php';
            break;
    }
?>