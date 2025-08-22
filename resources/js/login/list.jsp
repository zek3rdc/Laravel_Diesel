<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt" %>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - Tienda de Conveniencia</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand i {
            margin-right: 0.5rem;
            font-size: 1.8rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: var(--secondary-color);
        }

        .nav-link.active {
            color: white;
            background: rgba(52, 152, 219, 0.2);
            border-left-color: var(--secondary-color);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Top Navigation */
        .top-nav {
            background: white;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .nav-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--dark-text);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .nav-toggle:hover {
            background-color: var(--light-bg);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-text);
            margin: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
        }

        .user-info {
            margin-right: 1rem;
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: var(--dark-text);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1);
        }

        /* Content Area */
        .content-area {
            padding: 2rem 1.5rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-text);
            margin: 0;
        }

        /* Filters */
        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Table */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            overflow-x: auto; /* Habilitar scroll horizontal */
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }

        .badge.bg-success {
            background-color: var(--success-color) !important;
        }

        .badge.bg-warning {
            background-color: var(--warning-color) !important;
        }

        .badge.bg-danger {
            background-color: var(--accent-color) !important;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-danger {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        /* Stock indicators */
        .stock-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stock-low {
            color: var(--accent-color);
        }

        .stock-medium {
            color: var(--warning-color);
        }

        .stock-good {
            color: var(--success-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .top-nav {
                padding: 1rem;
            }

            .content-area {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <jsp:include page="../layout/sidebar.jsp" />

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="d-flex align-items-center">
                <button class="nav-toggle" id="navToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title ms-3">Gestión de Categorías</h1>
            </div>
            
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name">${sessionScope.CURRENT_USER.nombreCompleto}</div>
                    <div class="user-role">${sessionScope.CURRENT_USER_ROLE}</div>
                </div>
                <div class="user-avatar">
                    ${sessionScope.CURRENT_USER.nombreCompleto.substring(0,1).toUpperCase()}
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Messages -->
            <c:if test="${not empty sessionScope.MESSAGE_SUCCESS}">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    ${sessionScope.MESSAGE_SUCCESS}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <c:remove var="MESSAGE_SUCCESS" scope="session"/>
            </c:if>

            <c:if test="${not empty sessionScope.MESSAGE_ERROR}">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${sessionScope.MESSAGE_ERROR}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <c:remove var="MESSAGE_ERROR" scope="session"/>
            </c:if>

            <!-- Filters and Actions -->
            <div class="filters-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <form method="GET" action="${pageContext.request.contextPath}/categorias" class="d-flex gap-2">
                            <input type="hidden" name="action" value="search">
                            <input type="text" class="form-control" name="q" placeholder="Buscar categorías..." 
                                   value="${searchQuery}" style="max-width: 300px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <c:if test="${sessionScope.CURRENT_USER_ROLE == 'ADMIN' || sessionScope.CURRENT_USER_ROLE == 'EMPLEADO'}">
                            <a href="${pageContext.request.contextPath}/categorias?action=create" class="btn btn-success">
                                <i class="fas fa-plus"></i> Nueva Categoría
                            </a>
                        </c:if>
                        <button class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-times"></i> Limpiar Búsqueda
                        </button>
                    </div>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-tags me-2"></i>
                        Lista de Categorías
                        <span class="badge bg-primary ms-2">${totalCategorias} categorías</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Categoría Padre</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <c:choose>
                                    <c:when test="${empty categorias}">
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No se encontraron categorías</p>
                                                <c:if test="${sessionScope.CURRENT_USER_ROLE == 'ADMIN' || sessionScope.CURRENT_USER_ROLE == 'EMPLEADO'}">
                                                    <a href="${pageContext.request.contextPath}/categorias?action=create" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Agregar Primera Categoría
                                                    </a>
                                                </c:if>
                                            </td>
                                        </tr>
                                    </c:when>
                                    <c:otherwise>
                                        <c:forEach var="categoria" items="${categorias}">
                                            <tr>
                                                <td>${categoria.id}</td>
                                                <td>
                                                    <strong>${categoria.nombre}</strong>
                                                    <c:if test="${not empty categoria.descripcion}">
                                                        <br><small class="text-muted">${categoria.descripcion}</small>
                                                    </c:if>
                                                </td>
                                                <td>${categoria.descripcion}</td>
                                                <td>
                                                    <c:if test="${categoria.parentId != null}">
                                                        <c:set var="parentCategory" value="${null}"/>
                                                        <c:forEach var="cat" items="${categorias}">
                                                            <c:if test="${cat.id == categoria.parentId}">
                                                                <c:set var="parentCategory" value="${cat}"/>
                                                            </c:if>
                                                        </c:forEach>
                                                        <c:choose>
                                                            <c:when test="${parentCategory != null}">
                                                                ${parentCategory.nombre}
                                                            </c:when>
                                                            <c:otherwise>
                                                                <span class="text-muted">ID: ${categoria.parentId} (No disponible)</span>
                                                            </c:otherwise>
                                                        </c:choose>
                                                    </c:if>
                                                    <c:if test="${categoria.parentId == null}">
                                                        <span class="text-muted">N/A (Categoría Raíz)</span>
                                                    </c:if>
                                                </td>
                                                <td>
                                                    <c:choose>
                                                        <c:when test="${categoria.activo}"><span class="badge bg-success">Activo</span></c:when>
                                                        <c:otherwise><span class="badge bg-secondary">Inactivo</span></c:otherwise>
                                                    </c:choose>
                                                </td>
                                                <td><fmt:formatDate value="${categoria.fechaCreacion}" pattern="dd/MM/yyyy HH:mm"/></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="${pageContext.request.contextPath}/categorias?action=edit&id=${categoria.id}" class="btn btn-sm btn-outline-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                                        <c:if test="${sessionScope.CURRENT_USER_ROLE == 'ADMIN'}">
                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                                                    data-category-id="${categoria.id}" 
                                                                    data-category-name="${categoria.nombre}" 
                                                                    title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </c:if>
                                                    </div>
                                                </td>
                                            </tr>
                                        </c:forEach>
                                    </c:otherwise>
                                </c:choose>
                            </tbody>
                        </table>
                    </div>
                    <!-- Paginación -->
                    <c:if test="${totalPages > 1}">
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Página ${currentPage} de ${totalPages} (${totalCategorias} categorías)
                            </div>
                            <nav>
                                <ul class="pagination mb-0">
                                    <li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                                        <a class="page-link" href="?page=${currentPage - 1}&q=${searchQuery}">Anterior</a>
                                    </li>
                                    <c:forEach begin="1" end="${totalPages}" var="i">
                                        <li class="page-item ${currentPage == i ? 'active' : ''}">
                                            <a class="page-link" href="?page=${i}&q=${searchQuery}">${i}</a>
                                        </li>
                                    </c:forEach>
                                    <li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
                                        <a class="page-link" href="?page=${currentPage + 1}&q=${searchQuery}">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </c:if>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar la categoría <strong id="categoryName"></strong>?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteCategoryId">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="${pageContext.request.contextPath}/resources/js/main.js"></script>
    
    <script>
        function clearFilters() {
            window.location.href = '${pageContext.request.contextPath}/categorias?action=list';
        }

        // Delete confirmation
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category-id');
                const categoryName = this.getAttribute('data-category-name');
                
                document.getElementById('categoryName').textContent = categoryName;
                document.getElementById('deleteCategoryId').value = categoryId;
                document.getElementById('deleteForm').action = '${pageContext.request.contextPath}/categorias';
                
                deleteModal.show();
            });
        });
    </script>
</body>
</html>