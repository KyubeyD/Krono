document.addEventListener("DOMContentLoaded", () => {
    // Selecionar botões de edição
    const editButtons = document.querySelectorAll(".btn-crud.edit");
    const viewButtons = document.querySelectorAll(".btn-crud.view");

    // Função para editar os dados
    editButtons.forEach(button => {
        button.addEventListener("click", async () => {
            const tipo = button.getAttribute("data-tipo");
            const id = button.getAttribute("data-id");
            const modal = document.getElementById("modalEdit");
            const modalTitle = modal.querySelector(".modal-title");
            const form = modal.querySelector("form");

            // Limpar o formulário
            form.innerHTML = "";

            try {
                const response = await fetch(`PAGES/COMPONENTS/fetchDataEdit.php?tipo=${tipo}&id=${id}`);
                if (!response.ok) throw new Error("Erro ao buscar os dados");
                const data = await response.json();

                // Configurar modal com base no tipo
                switch (tipo) {
                    case "curso":
                        modalTitle.textContent = "Editar Curso";
                        form.action = `PAGES/CURSO/atualizarCurso.php?id_curso=${id}`;
                        form.innerHTML = `
                            <div class="campo">
                                <label class="modal-label" for="nome_curso">Nome do Curso:</label>
                                <input type="text" name="nome_curso" id="nome_curso" class="modal-form" value="${data.nome_curso}" required>
                            </div>
                            <div class="campo">
                                <label class="modal-label" for="sigla_curso">Sigla:</label>
                                <input type="text" name="sigla_curso" id="sigla_curso" class="modal-form" value="${data.sigla_curso}" required>
                            </div>
                            <input type="submit" class="btn-modal" value="Salvar">
                        `;
                        break;
                   case "disciplina":
                        modalTitle.textContent = "Editar Disciplina";
                        form.action = `PAGES/DISCIPLINA/atualizarDisciplina.php?id_disciplina=${id}`;
                        form.innerHTML = `
                            <div class="campo">
                                <label class="modal-label" for="nome_disciplina">Nome da Disciplina:</label>
                                <input type="text" name="nome_disciplina" id="nome_disciplina" class="modal-form" value="${data.nome_disciplina}" required>
                            </div>
                            <div class="campo">
                                <label class="modal-label" for="sigla_disciplina">Sigla:</label>
                                <input type="text" name="sigla_disciplina" id="sigla_disciplina" class="modal-form" value="${data.sigla_disciplina}" required>
                            </div>
                            <div class="campo">
                                <label class="modal-label" for="carga_horaria">Carga Horária:</label>
                                <input type="number" name="carga_horaria" id="carga_horaria" class="modal-form" value="${data.carga_horaria}" required>
                            </div>
                            <div class="campo">
                                <label class="modal-label">Cursos:</label>
                                <div class="checkbox-modal">
                                    ${data.cursos.map(curso => `
                                        <div>
                                            <input class="checkbox" type="checkbox" name="cursos[]" value="${curso.id_curso}" id="curso_${curso.id_curso}" ${curso.checado ? "checked" : ""}>
                                            <label for="curso_${curso.id_curso}">${curso.nome_curso}</label>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            <input type="submit" class="btn-modal" value="Salvar">
                        `;
                        break;
                        case "turma":
                            modalTitle.textContent = "Editar Turma";
                            form.action = `PAGES/TURMA/atualizarTurma.php?id_turma=${id}`;
                            form.innerHTML = `
                                <div class="campo">
                                    <label class="modal-label" for="nome_turma">Nome da Turma:</label>
                                    <input type="text" name="nome_turma" id="nome_turma" class="modal-form" value="${data.nome_turma}" required>
                                </div>
                                <div class="campo">
                                    <label class="modal-label" for="serie_turma">Série:</label>
                                    <input type="text" name="serie_turma" id="serie_turma" class="modal-form" value="${data.serie_turma}" required>
                                </div>
                                <div class="campo">
                                    <label class="modal-label" for="id_escola">Escola:</label>
                                    <input type="text" name="id_escola" id="id_escola" class="modal-form" value="${data.nome_escola}" disabled>
                                </div>
                                <div class="campo">
                                    <label class="modal-label" for="id_curso">Curso:</label>
                                    <input type="text" name="id_curso" id="id_curso" class="modal-form" value="${data.nome_curso}" disabled>
                                </div>
                                <input type="submit" class="btn-modal" value="Salvar">
                            `;
                            break;
                        case "regra_disp":
                            modalTitle.textContent = "Editar Regra";
                            form.action = `PAGES/REGRA/atualizarRegra.php?id_regra=${id}`;
                            form.innerHTML = `
                                                                <div class="campo">
                                    <label class="modal-label" for="nome_regra">Nome da regra:</label>
                                    <input type="text" name="nome_regra" id="nome_regra" class="modal-form" value="${data.nome_regra}" required>
                                </div>
                                <div class="campo">
                                    <label class="modal-label" for="descricao">Descrição:</label>
                                    <textarea name="descricao" id="descricao" class="modal-form" required>${data.descricao}</textarea>
                                </div>
                                <div class="campo">
                                    <label class="modal-label" for="importante">Importante:</label>
                                    <input type="text" name="importante" id="importante" class="modal-form" value="${data.importante}" required>
                                </div>
                                <input type="submit" class="btn-modal" value="Salvar">`
                            ;
                            break;
    
                        case "carga_horaria_esc":
                            modalTitle.textContent = "Editar Carga Horária";
                            form.action = `PAGES/REGRA/atualizarCarga.php?id_carga_horaria_esc=${id}`;
                            form.innerHTML = `
                                <div class="campo">
                                    <label class="modal-label" for="horas_semanais">Horas semanais:</label>
                                    <input type="number" name="horas_semanais" id="horas_semanais" class="modal-form" value="${data.horas_semanais}" required>
                                </div>
                                <input type="submit" class="btn-modal" value="Salvar">
                            `;

                            break;
                    
                    default:
                        throw new Error("Tipo de entidade desconhecido");
                }

                // Mostrar modal
                modal.style.display = "block";
            } catch (error) {
                console.error(error);
                alert("Erro ao carregar os dados para edição.");
            }
        });
    });

    // Função para visualizar os dados
    viewButtons.forEach(button => {
        button.addEventListener("click", async () => {
            const tipo = button.getAttribute("data-tipo");
            const id = button.getAttribute("data-id");
            const modal = document.getElementById("modalEdit");
            const modalTitle = modal.querySelector(".modal-title");
            const form = modal.querySelector("form");

            // Limpar o formulário
            form.innerHTML = "";

            try {
                const response = await fetch(`PAGES/COMPONENTS/fetchDataView.php?tipo=${tipo}&id=${id}`);
                if (!response.ok) throw new Error("Erro ao buscar os dados");
                const data = await response.json();

                // Configurar modal com base no tipo
                switch (tipo) {
                    case "professor":
                        modalTitle.textContent = `${data.nome_professor}`;
                        form.innerHTML = `
                        <div class="campo">
                            <label class="modal-label">Foto:</label>
                            ${data.foto_usuario ? `<img class='foto-view' src="IMG/UPLOADS/${data.foto_usuario}" alt="Foto do Professor" class="foto-professor">` : `<p>Sem foto disponível</p>`}
                        </div>
                            <div class="campo">
                                <label class="modal-label">Nome do Professor:</label>
                                <p>${data.nome_professor}</p>
                            </div>
                            <div class="campo">
                                <label class="modal-label">E-mail:</label>
                                <p>${data.email_professor}</p>
                            </div>
                            <div class="campo">
                                <label class="modal-label">Telefone:</label>
                                <p>${data.telefone_professor}</p>
                            </div>
                            <div class="campo">
                                <label class="modal-label">Status:</label>
                                <p>${data.status_usuario ? 'Ativo' : 'Inativo'}</p>
                            </div>
                            <div class="campo">
                                <label class="modal-label">Disciplinas:</label>
                                <ul>
                                    ${(Array.isArray(data.disciplinas) && data.disciplinas.length > 0) 
                                        ? data.disciplinas.map(disciplina => `<li>${disciplina.nome_disciplina}</li>`).join('') 
                                        : '<li>Nenhuma disciplina vinculada</li>'}
                                </ul>
                            </div>
                        `;
                        break;
                    case "curso":
                        modalTitle.textContent = `${data.nome_curso}`;
                        form.innerHTML = `
                            <div class="campo">
                                <label class="modal-label">Nome do Curso:</label>
                                <p>${data.nome_curso}</p>
                            </div>
                            <div class="campo">
                                <label class="modal-label">Sigla:</label>
                                <p>${data.sigla_curso}</p>
                            </div>
                            <div class="campo">
                                <label class="modal-label">Disciplinas:</label>
                                <ul>
                                    ${data.disciplinas.map(disciplina => `
                                        <li>${disciplina.nome_disciplina}</li>
                                    `).join('')}
                                </ul>
                            </div>
                        `;
                        break;
                        case "disciplina":
                            modalTitle.textContent = `${data.nome_disciplina}`;
                            form.innerHTML = `
                                <div class="campo">
                                    <label class="modal-label">Nome da Disciplina:</label>
                                    <p>${data.nome_disciplina}</p>
                                </div>
                                <div class="campo">
                                    <label class="modal-label">Sigla:</label>
                                    <p>${data.sigla_disciplina}</p>
                                </div>
                                <div class="campo">
                                    <label class="modal-label">Carga Horária:</label>
                                    <p>${data.carga_horaria} horas</p>
                                </div>
                                <div class="campo">
                                    <label class="modal-label">Cursos Associados:</label>
                                    <ul>
                                        ${data.cursos.map(curso => `
                                            <li>${curso.nome_curso}</li>
                                        `).join('')}
                                    </ul>
                                </div>
                            `;
                            break;

                    default:
                        throw new Error("Tipo de entidade desconhecido");
                }

                // Mostrar modal
                modal.style.display = "block";
            } catch (error) {
                console.error(error);
                alert("Erro ao carregar os dados para visualização.");
            }
        });
    });

    // Fechar modal ao clicar no botão de fechar
    document.querySelectorAll(".modal .close").forEach(closeButton => {
        closeButton.addEventListener("click", () => {
            closeButton.closest(".modal").style.display = "none";
        });
    });
});
