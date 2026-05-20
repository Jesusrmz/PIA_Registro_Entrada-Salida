-- =========================================================
-- 1. CREACIÓN DE LA BASE DE DATOS
-- =========================================================
CREATE DATABASE db_Registros;
GO
USE db_Registros;
GO

-- =========================================================
-- 2. CREACIÓN DE TABLAS 
-- =========================================================

CREATE TABLE Puestos (
    id_puesto INT PRIMARY KEY IDENTITY(1,1),
    nombre_puesto VARCHAR(100) NOT NULL
);

CREATE TABLE Usuarios (
    id_usuario INT PRIMARY KEY IDENTITY(1,1),
    username VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL, 
    id_puesto INT,
    fecha_nacimiento DATE NOT NULL,
    CONSTRAINT FK_Usuarios_Puestos FOREIGN KEY (id_puesto) 
        REFERENCES Puestos(id_puesto) ON DELETE SET NULL
);

CREATE TABLE Asistencia (
    id_registro INT PRIMARY KEY IDENTITY(1,1),
    id_usuario INT NOT NULL,
    fecha DATE DEFAULT CAST(GETDATE() AS DATE), 
    hora_entrada DATETIME NULL,
    hora_salida DATETIME NULL,
    -- Restricción de Llave Foránea
    CONSTRAINT FK_Asistencia_Usuarios FOREIGN KEY (id_usuario) 
        REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE Log_Usuarios (
    id_log INT PRIMARY KEY IDENTITY(1,1),
    id_usuario_nuevo INT NOT NULL,
    fecha_registro DATETIME DEFAULT GETDATE(),
    accion VARCHAR(150) DEFAULT 'Nuevo usuario registrado en el sistema web'
);
GO

-- =========================================================
-- 3. CREACIÓN DEL TRIGGER 
-- =========================================================
CREATE TRIGGER trg_LogNuevoUsuario
ON Usuarios
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    INSERT INTO Log_Usuarios (id_usuario_nuevo)
    SELECT id_usuario FROM inserted;
END;
GO

-- =========================================================
-- 4. PROCEDIMIENTO ALMACENADO PARA LOGIN 
-- =========================================================
CREATE PROCEDURE sp_LoginUsuario
    @username VARCHAR(50)
AS
BEGIN
    SET NOCOUNT ON;
    SELECT id_usuario, username, password_hash, id_puesto
    FROM Usuarios 
    WHERE username = @username;
END;
GO

-- =========================================================
-- 5. INSERCIÓN DE PUESTOS DE TRABAJO 
-- =========================================================
INSERT INTO Puestos (nombre_puesto) VALUES ('Operador de Produccion');
INSERT INTO Puestos (nombre_puesto) VALUES ('Supervisor de Turno');
INSERT INTO Puestos (nombre_puesto) VALUES ('Recursos Humanos');
INSERT INTO Puestos (nombre_puesto) VALUES ('Administrador de Sistemas');
GO


