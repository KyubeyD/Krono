<?php
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_GET['tipo']) && isset($_GET['id'])) {
    $tipo = $_GET['tipo'];
    $id = (int)$_GET['id'];

    try {
        $data = [];

        switch ($tipo) {
            case "professor":
                // Consulta para obter os dados do professor
                $queryProfessor = "
                    SELECT p.nome_professor, p.email_professor, p.telefone_professor, u.foto_usuario, u.status_usuario 
                    FROM professor p
                    INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                    WHERE p.id_professor = :id
                ";
                $stmtProfessor = $conexao->prepare($queryProfessor);
                $stmtProfessor->bindValue(':id', $id, PDO::PARAM_INT);
                $stmtProfessor->execute();
                $professor = $stmtProfessor->fetch(PDO::FETCH_ASSOC);
            
                if ($professor) {
                    $data['nome_professor'] = $professor['nome_professor'];
                    $data['email_professor'] = $professor['email_professor'];
                    $data['telefone_professor'] = $professor['telefone_professor'];
                    $data['foto_usuario'] = $professor['foto_usuario'];
                    $data['status_usuario'] = $professor['status_usuario'];
                } else {
                    throw new Exception("Professor não encontrado");
                }
            
                // Consulta para obter as disciplinas do professor
                $queryDisciplinasProfessor = "
                    SELECT d.nome_disciplina 
                    FROM vinculo_prof_disc vpd
                    INNER JOIN disciplina d ON vpd.id_disciplina = d.id_disciplina
                    WHERE vpd.id_professor = :id
                ";
                $stmtDisciplinasProfessor = $conexao->prepare($queryDisciplinasProfessor);
                $stmtDisciplinasProfessor->bindValue(':id', $id, PDO::PARAM_INT);
                $stmtDisciplinasProfessor->execute();
                $disciplinas = $stmtDisciplinasProfessor->fetchAll(PDO::FETCH_ASSOC);
                $data['disciplinas'] = $disciplinas;
            
                break;
            case "curso":
                // Consulta para obter os dados do curso
                $queryCurso = "SELECT nome_curso, sigla_curso FROM curso WHERE id_curso = :id";
                $stmtCurso = $conexao->prepare($queryCurso);
                $stmtCurso->bindValue(':id', $id, PDO::PARAM_INT);
                $stmtCurso->execute();
                $curso = $stmtCurso->fetch(PDO::FETCH_ASSOC);

                if ($curso) {
                    $data['nome_curso'] = $curso['nome_curso'];
                    $data['sigla_curso'] = $curso['sigla_curso'];
                } else {
                    throw new Exception("Curso não encontrado");
                }

                // Consulta para obter as disciplinas do curso
                $queryDisciplinas = "
                    SELECT d.nome_disciplina 
                    FROM vinculo_curso_disc vcd
                    INNER JOIN disciplina d ON vcd.id_disciplina = d.id_disciplina
                    WHERE vcd.id_curso = :id
                ";
                $stmtDisciplinas = $conexao->prepare($queryDisciplinas);
                $stmtDisciplinas->bindValue(':id', $id, PDO::PARAM_INT);
                $stmtDisciplinas->execute();
                $disciplinas = $stmtDisciplinas->fetchAll(PDO::FETCH_ASSOC);

                $data['disciplinas'] = $disciplinas;
                break;
                case "disciplina":
                    // Consulta para obter os dados da disciplina
                    $queryDisciplina = "
                        SELECT d.nome_disciplina, d.sigla_disciplina, d.carga_horaria 
                        FROM disciplina d
                        WHERE d.id_disciplina = :id
                    ";
                    $stmtDisciplina = $conexao->prepare($queryDisciplina);
                    $stmtDisciplina->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmtDisciplina->execute();
                    $disciplina = $stmtDisciplina->fetch(PDO::FETCH_ASSOC);
    
                    if ($disciplina) {
                        $data['nome_disciplina'] = $disciplina['nome_disciplina'];
                        $data['sigla_disciplina'] = $disciplina['sigla_disciplina'];
                        $data['carga_horaria'] = $disciplina['carga_horaria'];
                    } else {
                        throw new Exception("Disciplina não encontrada");
                    }
    
                    // Consulta para obter os cursos associados à disciplina
                    $queryCursos = "
                        SELECT c.nome_curso 
                        FROM vinculo_curso_disc vcd
                        INNER JOIN curso c ON vcd.id_curso = c.id_curso
                        WHERE vcd.id_disciplina = :id
                    ";
                    $stmtCursos = $conexao->prepare($queryCursos);
                    $stmtCursos->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmtCursos->execute();
                    $cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);
    
                    $data['cursos'] = $cursos;
                    break;
                    case "turma":
                        // Consulta para obter os dados da turma
                        $queryTurma = "
                            SELECT t.nome_turma, t.serie_turma, e.nome_escola, c.nome_curso 
                            FROM turma t
                            INNER JOIN escola e ON t.id_escola = e.id_escola
                            INNER JOIN curso c ON t.id_curso = c.id_curso
                            WHERE t.id_turma = :id
                        ";
                        $stmtTurma = $conexao->prepare($queryTurma);
                        $stmtTurma->bindValue(':id', $id, PDO::PARAM_INT);
                        $stmtTurma->execute();
                        $turma = $stmtTurma->fetch(PDO::FETCH_ASSOC);
        
                        if ($turma) {
                            $data['nome_turma'] = $turma['nome_turma'];
                            $data['serie_turma'] = $turma['serie_turma'];
                            $data['nome_escola'] = $turma['nome_escola'];
                            $data['nome_curso'] = $turma['nome_curso'];
                        } else {
                            throw new Exception("Turma não encontrada");
                        }
                        break;
            default:
                throw new Exception("Tipo inválido");
        }

        // Retornar os dados como JSON
        if (!empty($data)) {
            echo json_encode($data);
        } else {
            throw new Exception("Nenhum dado encontrado");
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Parâmetros inválidos']);
}
