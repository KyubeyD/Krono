<?php
    if(!isset($_SESSION)) session_start();

    switch ($_SESSION['nivel_usuario']) {
        case '1':
            $nivel_necessario = 1;
            break;
        case '2':
            $nivel_necessario = 2;
            break;
        case '3':
            $nivel_necessario = 3;
            break;
    }

    require_once 'CREDENTIALS/testa_nivel.php';
?>
<div class="sidebar">
    <nav>
        <div class="link-logo">
            <figure class="logo">
                <img src="IMG/LOGO KRONO.png" alt="Krono">
            </figure>

            <ul class="side-links">
                <li class="side-item">
                    <a href="?page=dashboard">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <?php if ($nivel_necessario === 3): ?>
                <li class="side-item">
                    <a href="?page=users">
                        <i class='bx bx-user'></i>
                        <span class="text">Usuários</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="?page=escolas">
                        <i class='bx bxs-school'></i>
                        <span class="text">Escolas</span>
                    </a>
                </li>
                <?php 
                    endif;
                    if ($nivel_necessario >= 2): 
                ?>
                <li class="side-item">
                    <a href="?page=grades_horarias">
                        <i class='bx bx-time-five'></i>
                        <span class="text">Grades horárias</span>
                    </a>   
                </li>
                <?php 
                    endif;
                    if ($nivel_necessario === 2):
                ?>
                <li class="side-item">
                    <a href="?page=meu_horario_escola">
                        <i class='bx bxs-school'></i>
                        <span class="text">Meu horário</span>
                    </a>
                </li>
                <?php 
                    endif;
                    if ($nivel_necessario === 1):
                ?>
                <li class="side-item">
                    <a href="?page=meu_horario_professor">
                        <i class='bx bxs-graduation'></i>
                        <span class="text">Meu horário</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="?page=disponibilidade_professor">
                        <i class='bx bx-time-five'></i>
                        <span class="text">Disponibilidade</span>
                    </a>   
                </li>
                <?php 
                    endif;
                    if ($nivel_necessario === 2):
                ?>
                <li class="side-item">
                    <a href="?page=minhasOrientacoes">
                        <i class='bx bx-food-menu'></i>
                        <span class="text">Orientações</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="?page=professores">
                        <i class='bx bxs-graduation'></i>
                        <span class="text">Professores</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="?page=turmas">
                        <i class='bx bx-group'></i>
                        <span class="text">Turmas</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="?page=cursos">
                        <i class='bx bx-book'></i>
                        <span class="text">Cursos</span>
                    </a>   
                </li>
                <li class="side-item">
                    <a href="?page=disciplinas">
                        <i class='bx bx-math'></i>
                        <span class="text">Disciplinas</span>
                    </a>  
                </li>
                <?php 
                    endif; 
                ?>
                <?php 
                    if ($nivel_necessario === 3):     
                ?>
                <li class="side-item">
                    <a href="#">
                        <i class='bx bx-wallet'></i>
                        <span class="text">Financeiro</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="side-item">
                    <a href="?page=notificacao">
                        <i class='bx bx-bell' ></i>
                        <span class="text">Notificação</span>
                    </a>
                </li>
                <li class="side-item">
                    <a href="#">
                        <i class='bx bx-wrench'></i>
                        <span class="text">Suporte</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<script src="JS/sidebar.js"></script>