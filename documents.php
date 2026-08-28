<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

$pageTitle = 'Documents';
$activePage = 'documents';

$uploadError = $_SESSION['upload_error'] ?? null;
$uploadSuccess = $_SESSION['upload_success'] ?? null;
unset($_SESSION['upload_error'], $_SESSION['upload_success']);

$documents = $db->query(
    "SELECT * FROM documents WHERE id_user = ? ORDER BY uploaded_at DESC",
    [$_SESSION['user_id']]
)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/layout_top.php';
?>

                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title mb-0">
                                <i class="bi bi-folder me-2"></i>Documents de <?= htmlspecialchars($user['company_name']) ?>
                            </h3>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                <i class="bi bi-upload me-1"></i> Ajouter un document
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if ($uploadSuccess): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($uploadSuccess) ?></div>
                            <?php endif; ?>
                            <?php if ($uploadError): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($uploadError) ?></div>
                            <?php endif; ?>

                            <?php if (empty($documents)): ?>
                                <p class="text-secondary mb-0">Aucun document n'a encore été déposé.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Document</th>
                                                <th>Taille</th>
                                                <th>Déposé le</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($documents as $document): ?>
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-file-earmark-text me-2 text-secondary"></i>
                                                        <?= htmlspecialchars($document['original_name']) ?>
                                                    </td>
                                                    <td><?= htmlspecialchars(format_file_size((int) $document['file_size'])) ?></td>
                                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($document['uploaded_at']))) ?></td>
                                                    <td class="text-end">
                                                        <a
                                                            href="controllers/view_document.php?id=<?= (int) $document['id_document'] ?>"
                                                            class="btn btn-sm btn-outline-primary"
                                                            target="_blank"
                                                            rel="noopener"
                                                        >
                                                            <i class="bi bi-eye me-1"></i> Consulter
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!--begin::Upload Document Modal-->
                    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="controllers/upload_document.php" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="uploadDocumentModalLabel">Ajouter un document</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="document" class="form-label">Fichier</label>
                                            <input type="file" class="form-control" id="document" name="document" required />
                                            <div class="form-text">
                                                Formats acceptés : PDF, Word, Excel, PowerPoint, images, texte, archive ZIP. Taille maximale : 10 Mo.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-upload me-1"></i> Envoyer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end::Upload Document Modal-->

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
