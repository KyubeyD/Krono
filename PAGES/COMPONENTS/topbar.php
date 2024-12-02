<link rel="stylesheet" href="CSS/searchbar.css">
<div class="topbar">
    <div class="search-container">
        <input type="text" id="search" placeholder="Pesquisar no Krono...">
        <ul id="suggestions" class="suggestions"></ul>
    </div>

    <div class="profile">
        <?php
        // Verifica se os dados do usuário estão na sessão
        $nome_usuario = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Admin';
        $foto_usuario = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'default.jpg';
        ?>
        
        <span class="nome-perfil"><?php echo htmlspecialchars($nome_usuario); ?></span>
        <figure class="foto-perfil">
            <img src="IMG/UPLOADS/<?php echo htmlspecialchars($foto_usuario); ?>" alt="Foto de <?php echo htmlspecialchars($nome_usuario); ?>">
        </figure>
        <ul class="profile-nav">
            <li>
                <a href="?page=perfil">
                    <i class='bx bx-user-circle'></i>
                    <span>Perfil</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-cog'></i>
                    <span class="text">Configurações</span>
                </a>
            </li>
            <li class="logout">
                <a href="?page=logout">
                    <i class='bx bx-log-out'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<script src="JS/searchbar.js"></script>
<script src="JS/profile.js"></script>