CREATE DATABASE frattellisingluten;
USE frattellisingluten;

CREATE TABLE Ubicaciones (
    UbicacionID INT AUTO_INCREMENT PRIMARY KEY,
    ubicacion VARCHAR(100) NOT NULL,
    casa_departamento VARCHAR(50),
    numero VARCHAR(20),
    piso VARCHAR(20)
);

CREATE TABLE Usuarios (
    UsuarioID INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    admin BOOLEAN DEFAULT FALSE,
    UbicacionID INT,
    FOREIGN KEY (UbicacionID)
        REFERENCES Ubicaciones(UbicacionID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE Pedidos (
    PedidoID INT AUTO_INCREMENT PRIMARY KEY,
    FechaPedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UsuarioID INT NOT NULL,
    FOREIGN KEY (UsuarioID)
        REFERENCES Usuarios(UsuarioID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Productos (
    ProductoID INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    stock_producto INT NOT NULL DEFAULT 0,
    precio DECIMAL(10,2) NOT NULL
);

CREATE TABLE DetallePedido (
    DetalleID INT AUTO_INCREMENT PRIMARY KEY,
    PedidoID INT NOT NULL,
    ProductoID INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (PedidoID)
        REFERENCES Pedidos(PedidoID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (ProductoID)
        REFERENCES Productos(ProductoID)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


CREATE TABLE Ingredientes (
    IngredienteID INT AUTO_INCREMENT PRIMARY KEY,
    nombre_ingrediente VARCHAR(100) NOT NULL,
    stock_ingrediente INT NOT NULL DEFAULT 0
);


CREATE TABLE Preparaciones (
    ProductoID INT NOT NULL,
    IngredienteID INT NOT NULL,
    cantidad_ingredientes DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (ProductoID, IngredienteID),

    FOREIGN KEY (ProductoID)
        REFERENCES Productos(ProductoID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (IngredienteID)
        REFERENCES Ingredientes(IngredienteID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

INSERT INTO Ubicaciones(ubicacion, casa_departamento, numero, piso)
VALUES ('Av. Siempre Viva', 'Casa', '742', 'PB');

INSERT INTO Usuarios(nombre_usuario, correo, contraseña, admin, UbicacionID)
VALUES ('Juan Perez','juan@gmail.com','1234',0,1);

INSERT INTO Productos(nombre_producto, stock_producto, precio)
VALUES
('Hamburguesa',50,3500),
('Papas Fritas',80,1800);

INSERT INTO Ingredientes(nombre_ingrediente, stock_ingrediente)
VALUES
('Pan',100),
('Carne',80),
('Queso',50),
('Papa',200);

INSERT INTO Preparaciones
VALUES
(1,1,1),
(1,2,1),
(1,3,2),
(2,4,3);

INSERT INTO Pedidos(UsuarioID)
VALUES(1);

INSERT INTO DetallePedido(PedidoID, ProductoID, cantidad, precio)
VALUES
(1,1,2,3500),
(1,2,1,1800);