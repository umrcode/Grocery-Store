-- QuickCart : An Online Retail Store --> Database schema with integrity constraints and data insertion

CREATE DATABASE IF NOT EXISTS QuickCart;
USE QuickCart;
-- DROP DATABASE QuickCart;

-- Customer table 
CREATE TABLE IF NOT EXISTS customer (
	customerID INT AUTO_INCREMENT PRIMARY KEY, 
    first_name VARCHAR(50) NOT NULL, -- name is a composite attribute having first_name & last_name
    last_name VARCHAR(50),
    address_street VARCHAR(100) NOT NULL, -- address is a composite attribute having street, city, state & pincode
	address_city VARCHAR(50) NOT NULL,
	address_state VARCHAR(50) NOT NULL,
    pincode INT NOT NULL,
    phone_no BIGINT UNIQUE NOT NULL, -- int not sufficient for 10 digits
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    age INT DEFAULT 0, -- Age is derived from DateOfBirth
    gender VARCHAR(10)
);

-- Update the Age column based on DateOfBirth -- Age is a derived attribute in our DB
SET SQL_SAFE_UPDATES = 0; -- safe mode OFF
UPDATE Customer
SET age = DATEDIFF(CURDATE(), dob) / 365;

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
	adminID INT AUTO_INCREMENT PRIMARY KEY,
    password VARCHAR(50) NOT NULL
);

-- Product Category table
CREATE TABLE IF NOT EXISTS productCategory (
	categoryID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    noOfProducts BIGINT NOT NULL DEFAULT 0
);

-- Product table
CREATE TABLE IF NOT EXISTS product (
	productID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0 CHECK (stock>=0),
    brand VARCHAR(50) NOT NULL,
    qty_bought INT NOT NULL DEFAULT 0,
    description VARCHAR(200) NOT NULL DEFAULT "A high-quality product.", -- TEXT doesn't have default value
    prod_image VARCHAR(200) NOT NULL,
    categoryID INT NOT NULL, -- not null --> every product must belong to some category
    FOREIGN KEY (categoryID) REFERENCES productCategory(categoryID) ON DELETE CASCADE 
    ON UPDATE CASCADE
    -- represents "falls under" relationship & product gets deleted, updated when category is changed
);

-- Delivery Agent table
CREATE TABLE IF NOT EXISTS deliveryAgent (
	agentID INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50),
    availabilityStatus VARCHAR(20) NOT NULL DEFAULT "Offline", -- 'Available', 'Busy', 'Offline'
    phone_no BIGINT UNIQUE NOT NULL, -- int not sufficient for 10 digits
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    age INT DEFAULT 0 -- Age is derived from DateOfBirth
);

-- Store table
CREATE TABLE IF NOT EXISTS store (
	storeID INT AUTO_INCREMENT PRIMARY KEY,
    address_street VARCHAR(100) NOT NULL, -- address is a composite attribute having street, city, state & pincode
    address_city VARCHAR(50) NOT NULL,
    address_state VARCHAR(50) NOT NULL,
    pincode INT NOT NULL
);

-- Order table
CREATE TABLE IF NOT EXISTS `order` (
	orderID INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(20) NOT NULL DEFAULT "Confirmed", -- 'Comfirmed', 'Packed and Shipped', 'Delivered'
    total_price DECIMAL(10, 2) NOT NULL,
    time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    location VARCHAR(255) NOT NULL,
    agentPayment INT NOT NULL DEFAULT 50,    payment_method VARCHAR(50) NOT NULL DEFAULT "Wallet", -- 'Wallet', 'Credit Card', 'Debit Card', 'Cash on Delivery'    customerID INT NOT NULL,
    agentID INT NOT NULL,
    FOREIGN KEY (customerID) REFERENCES customer(customerID), -- represents Customer “places” Order relationship
    FOREIGN KEY (agentID) REFERENCES deliveryAgent(agentID) -- represents Order “fulfilled by” DeliveryAgent relationship
);

-- Weak Entities :-

-- Wallet table
CREATE TABLE IF NOT EXISTS wallet (
	customerID INT NOT NULL,
    balance DECIMAL(10, 2) NOT NULL DEFAULT 0 CHECK (balance>=0),
    upiID VARCHAR(100) NOT NULL,
    rewardPoints INT NOT NULL DEFAULT 0,
    FOREIGN KEY (customerID) REFERENCES customer(customerID) ON DELETE CASCADE 
    -- represents Wallet “Belongs to” Customer identifying relationship & wallet gets deleted when customer deleted
);
-- DROP TABLE wallet; 

-- Delivery Agent Wallet table
CREATE TABLE IF NOT EXISTS delivery_agent_wallet (
    agentID INT NOT NULL,
    earning_balance DECIMAL(10, 2) NOT NULL DEFAULT 0 CHECK (earning_balance>=0),
    earning_paid DECIMAL(10, 2) NOT NULL DEFAULT 0 CHECK (earning_paid>=0),
    earning_total DECIMAL(10, 2) NOT NULL DEFAULT 0,
    Transaction_history VARCHAR(500),
    upiID VARCHAR(100) NOT NULL,
    FOREIGN KEY (agentID) REFERENCES deliveryAgent(agentID) ON DELETE CASCADE 
    -- represents Wallet “Belongs to” DeliveryAgent identifying relationship & wallet gets deleted when delivery agent deleted
);
-- DROP TABLE delivery_agent_wallet;

-- Product Review table
CREATE TABLE IF NOT EXISTS ProductReview (
    productReviewID INT NOT NULL, 
    orderID INT NOT NULL,
    customerID INT NOT NULL,
    comment TEXT,
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    FOREIGN KEY (orderID) REFERENCES `order`(orderID) ON DELETE CASCADE,
    FOREIGN KEY (customerID) REFERENCES customer(customerID) ON DELETE CASCADE
    -- represents Customer “purchases” Product Ternary relationship
);
-- DROP TABLE ProductReview;

-- Delivery Review table
CREATE TABLE IF NOT EXISTS DeliveryReview (
	deliveryReviewID INT NOT NULL, 
    orderID INT NOT NULL,
    agentID INT NOT NULL,
    comment TEXT,
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    tip DECIMAL(10, 2) DEFAULT 0,
    FOREIGN KEY (orderID) REFERENCES `order`(orderID) ON DELETE CASCADE,
    FOREIGN KEY (agentID) REFERENCES deliveryAgent(agentID) ON DELETE CASCADE 
    -- represents Customer “rates” Delivery Ternary relationship
);
-- DROP TABLE DeliveryReview;

-- Relations table

CREATE TABLE IF NOT EXISTS addsToCart (
    customerID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1 CHECK (quantity >= 1),
    PRIMARY KEY (customerID, productID),
    FOREIGN KEY (customerID) REFERENCES customer(customerID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES product(productID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orderConsistsProduct (
    orderID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1 CHECK (quantity >= 1),
    PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES `order`(orderID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES product(productID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS storeContainsProduct  (
    storeID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0 CHECK (quantity >= 0),
    PRIMARY KEY (productID, storeID),
    FOREIGN KEY (storeID) REFERENCES store(storeID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES product(productID) ON DELETE CASCADE
);

SHOW TABLES;
-- populating tables with data 

INSERT INTO customer (first_name, last_name, address_street, address_city, address_state, pincode, phone_no, email, password, dob, age, gender)
VALUES
("Muhammad", "Hassan", "Street 45, Fort", "Karachi", "Sindh", 74000, 3001234567, "hassan@quickcart.com", "Hassan@123$", "1995-05-15", 27, "Male"),
("Ayesha", "Khan", "Mall Road 78", "Lahore", "Punjab", 54000, 3021234568, "ayesha@quickcart.com", "Ayesha@123$", "1997-01-14", 25, "Female"),
("Ahmed", "Ali", "Sector 7, Blue", "Islamabad", "Islamabad", 44000, 3051234569, "ahmed@quickcart.com", "Ahmed@123$", "1995-06-30", 26, "Male"),
("Fatima", "Malik", "Subzi Mandi", "Rawalpindi", "Punjab", 46000, 3111234570, "fatima@quickcart.com", "Fatima@123$", "1991-09-08", 30, "Female"),
("Hassan", "Raza", "Chaman Gate", "Multan", "Punjab", 60000, 3001234571, "hasanraza@quickcart.com", "Hassan@123$", "1992-03-24", 29, "Male"),
("Zainab", "Sheikh", "Railway Road 34", "Faisalabad", "Punjab", 38000, 3411234572, "zainab@quickcart.com", "Zainab@123$", "1993-02-01", 29, "Female"),
("Ali", "Muhammad", "Cantonment Road", "Peshawar", "KP", 25000, 3091234573, "alim@quickcart.com", "Ali@123$", "1990-12-25", 34, "Male"),
("Hira", "Ahmed", "Zarghoon Road", "Quetta", "Balochistan", 87300, 3001234574, "hira@quickcart.com", "Hira@123$", "1999-08-18", 22, "Female"),
("Saira", "Khan", "Beach Road 56", "Karachi", "Sindh", 74100, 3001234575, "saira@quickcart.com", "Saira@123$", "1997-10-10", 25, "Female"),
("Omar", "Malik", "Clifton Avenue", "Karachi", "Sindh", 75600, 3001234576, "omar@quickcart.com", "Omar@123$", "1992-02-14", 32, "Male"),
("Amina", "Hussain", "New Garden Road", "Lahore", "Punjab", 54400, 3021234577, "amina@quickcart.com", "Amina@123$", "1990-09-08", 31, "Female"),
("Khalid", "Abbas", "Canal Road 23", "Lahore", "Punjab", 54300, 3021234578, "khalid@quickcart.com", "Khalid@123$", "1993-02-20", 29, "Male"),
("Sara", "Ali", "Tariq Road 45", "Karachi", "Sindh", 74200, 3001234579, "sara@quickcart.com", "Sara@123$", "1994-05-18", 28, "Female"),
("Imran", "Khan", "Parkway Road", "Islamabad", "Islamabad", 44100, 3051234580, "imran@quickcart.com", "Imran@123$", "1993-09-20", 31, "Male"),
("Mariam", "Ahmed", "Defence Road", "Lahore", "Punjab", 54700, 3021234581, "mariam@quickcart.com", "Mariam@123$", "1992-01-30", 30, "Female"),
("Bilal", "Hassan", "Jinnah Avenue", "Rawalpindi", "Punjab", 46100, 3111234582, "bilal@quickcart.com", "Bilal@123$", "1998-08-15", 26, "Male"),
("Noor", "Ibrahim", "Gulshan-e-Iqbal", "Karachi", "Sindh", 75290, 3001234583, "noor@quickcart.com", "Noor@123$", "1988-10-12", 33, "Female"),
("Rashid", "Khurram", "Data Gunj Baksh", "Lahore", "Punjab", 54500, 3021234584, "rashid@quickcart.com", "Rashid@123$", "1999-11-28", 25, "Male"),
("Laiba", "Nasir", "Abbasi Shaheed", "Rawalpindi", "Punjab", 46200, 3111234585, "laiba@quickcart.com", "Laiba@123$", "1988-07-12", 33, "Female"),
("Usman", "Farooq", "Gulistan-e-Johar", "Karachi", "Sindh", 75160, 3001234586, "usman@quickcart.com", "Usman@123$", "1992-09-22", 29, "Male"),
("Iqra", "Saleem", "Liberty Market", "Lahore", "Punjab", 54600, 3021234587, "iqra@quickcart.com", "Iqra@123$", "1997-01-14", 25, "Female"),
("Faisal", "Anwar", "Scheme 33", "Karachi", "Sindh", 75350, 3001234588, "faisal@quickcart.com", "Faisal@123$", "1990-09-15", 31, "Male"),
("Maha", "Waqar", "Saddar", "Rawalpindi", "Punjab", 46000, 3111234589, "maha@quickcart.com", "Maha@123$", "1995-06-30", 26, "Female"),
("Nadir", "Saleem", "Mall of Lahore", "Lahore", "Punjab", 54100, 3021234590, "nadir@quickcart.com", "Nadir@123$", "1990-05-12", 31, "Male"),
("Leila", "Rashid", "Nazimabad", "Karachi", "Sindh", 74600, 3001234591, "leila@quickcart.com", "Leila@123$", "1995-01-15", 27, "Female"),
("Sana", "Karim", "F-Block", "Islamabad", "Islamabad", 44400, 3051234592, "sana@quickcart.com", "Sana@123$", "1998-04-30", 24, "Female"),
("Wasim", "Akram", "G-9 Markaz", "Islamabad", "Islamabad", 44000, 3051234593, "wasim@quickcart.com", "Wasim@123$", "1994-05-18", 28, "Male"),
("Dina", "Farida", "Township 4", "Lahore", "Punjab", 54800, 3021234594, "dina@quickcart.com", "Dina@123$", "1995-05-01", 29, "Female"),
("Mirza", "Waqar", "Civic Centre", "Karachi", "Sindh", 74000, 3001234595, "mirza@quickcart.com", "Mirza@123$", "1997-01-14", 25, "Male"),
("Hina", "Javid", "Johar Town", "Lahore", "Punjab", 54800, 3021234596, "hina@quickcart.com", "Hina@123$", "1998-04-22", 24, "Female"),
("Samad", "Farooq", "Chaklala", "Rawalpindi", "Punjab", 46000, 3111234597, "samad@quickcart.com", "Samad@123$", "1987-07-22", 34, "Male"),
("Gul", "Noor", "Abbottabad Road", "Rawalpindi", "Punjab", 46100, 3111234598, "gul@quickcart.com", "Gul@123$", "1994-03-05", 28, "Female"),
("Tariq", "Hassan", "Gulshan", "Karachi", "Sindh", 75200, 3001234599, "tariq@quickcart.com", "Tariq@123$", "1988-01-09", 34, "Male"),
("Sadia", "Mirza", "Park Road 12", "Lahore", "Punjab", 54100, 3021234600, "sadia@quickcart.com", "Sadia@123$", "1996-12-12", 25, "Female"),
("Nasir", "Karim", "Thokar", "Multan", "Punjab", 60000, 3001234601, "nasir@quickcart.com", "Nasir@123$", "1997-03-11", 27, "Male"),
("Zara", "Ahmed", "Pir Waleed Road", "Lahore", "Punjab", 54000, 3021234602, "zara@quickcart.com", "Zara@123$", "1993-11-25", 29, "Female"),
("Babar", "Malik", "DHA", "Lahore", "Punjab", 54700, 3021234603, "babar@quickcart.com", "Babar@123$", "1991-12-10", 30, "Male"),
("Asma", "Nadeem", "North Nazimabad", "Karachi", "Sindh", 74700, 3001234604, "asma@quickcart.com", "Asma@123$", "1989-06-14", 33, "Female"),
("Karim", "Rauf", "Shahdara", "Lahore", "Punjab", 54800, 3021234605, "karim@quickcart.com", "Karim@123$", "1996-12-10", 25, "Male"),
("Rida", "Aziz", "Wapda Town", "Lahore", "Punjab", 54800, 3021234606, "rida@quickcart.com", "Rida@123$", "2000-10-22", 24, "Female"),
("Jamil", "Salim", "Askari", "Rawalpindi", "Punjab", 46000, 3111234607, "jamil@quickcart.com", "Jamil@123$", "1985-07-03", 36, "Male"),
("Sima", "Iftikhar", "Ghazni Street", "Lahore", "Punjab", 54000, 3021234608, "sima@quickcart.com", "Sima@123$", "1986-11-25", 35, "Female"),
("Hamid", "Nawaz", "Shaheen", "Islamabad", "Islamabad", 44000, 3051234609, "hamid@quickcart.com", "Hamid@123$", "1991-08-28", 30, "Male"),
("Needa", "Azam", "Muzaffarabad", "Muzaffarabad", "AJK", 13100, 3051234610, "needa@quickcart.com", "Needa@123$", "1990-09-15", 31, "Female"),
("Rashad", "Fareed", "Khayaban-e-Bahria", "Karachi", "Sindh", 75600, 3001234611, "rashad@quickcart.com", "Rashad@123$", "1993-02-01", 29, "Male"),
("Lina", "Sajid", "Allama Iqbal", "Lahore", "Punjab", 54100, 3021234612, "lina@quickcart.com", "Lina@123$", "1996-02-20", 26, "Female"),
("Wadud", "Ansar", "Garden Road", "Faisalabad", "Punjab", 38000, 3411234613, "wadud@quickcart.com", "Wadud@123$", "1993-06-14", 28, "Male"),
("Nasira", "Bashir", "Mozang", "Lahore", "Punjab", 54100, 3021234614, "nasira@quickcart.com", "Nasira@123$", "1985-11-18", 36, "Female"),
("Amir", "Jalil", "Sargodha Road", "Sargodha", "Punjab", 40100, 3001234615, "amir@quickcart.com", "Amir@123$", "1991-09-08", 30, "Male"),
("Rabia", "Karim", "Jauharabad", "Jhang", "Punjab", 35200, 3001234616, "rabia@quickcart.com", "Rabia@123$", "2002-06-18", 22, "Female");

UPDATE Customer
SET age = DATEDIFF(CURDATE(), dob) / 365;

SELECT * FROM customer;

INSERT INTO admin (password) VALUES
("Ali@Hassan123"),
("Faizan@Ali123");

SELECT * FROM admin;

INSERT INTO productCategory (name, noOfProducts) VALUES
('Dairy Products',28),
('Fruits & Vegetables',40),
('Munchies', 100),
('Sweets and Chocolates', 32),
('Health and Wellness', 40),
('Drinks and Juices', 25),
('Spices and Condiments',20),
('Beauty and Personal Care', 90),
('Home and Kitchen', DEFAULT),
('Books', 120),
('Toys and Games', 60);
    
SELECT * FROM productCategory;

INSERT INTO product (name, price, stock, brand, qty_bought, description, prod_image, categoryID) VALUES
('Cow Milk', 450, 20, 'Daraz', 7, "Pure and fresh cow's milk packed with great nutrition.","cow-milk.png", 1),
('Go Cheese', 5500, 8, 'Local Brand', 5, 'Yummy cheese that brings magin in every bite.', "go-cheese.png", 1),
('Carrot 500g', 250, 40, 'QuickCart', 14, 'Crunchy, sweet & tasty.', "carrot.png", 2),
('Diary Bars', 350, 2, 'Candyland', 0, 'Delicious chocolate bars!', "dairy-milk.png", 4),
('Aloo Bhujia', 550, 60, 'Chittor', 45, 'Crispy, crunchy snack that leaves you asking for more.', "aloo-bhujia.png", 3),
('French Fries', 1200, 40, 'Crispylite', 15, 'Crispy on the outside and fluffy in the centre, delicious in taste.', "french-fries.png", 3),
('Milk Chocolate Bar', 12500, 30, 'Galaxy', 8, 'Smooth chocolaty delight perfect to satisfy your sweet urges.', "milk-chocolate.jpg", 4),
('Antacid Powder', 550, 40, 'Gastrul', DEFAULT, 'Gets to work in 6 seconds to neutralize acid in your stomach and provide fast relief.', "eno.jpeg", 5),
('Mixed Fruit Juice 1L', 750, 25, 'Shan', 12, 'Filled with the best qualities of 9 different fruits, no added preservatives.', "mixed-fruit.png", 6),
('Cumin Seeds 100g', 450, 20, 'Whole Farm', 0, 'Cumin seeds/Jeera is used to give dishes a strong & spicy flavour.', "cumin-seeds.png", 7),
('Body Lotion 400ml', 1800, 60, 'Nivea', 40, 'Nourishes skin & provide long-lasting moisture.', "body-lotion.png", 8),
('Coconut Oil 250ml', 1200, 30, 'Parachute', 12, 'Nothing but 100% pure coconut oil.', "coconut-oil.jpg", 8),
('Three Men In A Boat', 1150, 120, 'Jerome K. Jerome', 22, 'Treat yourself with humour and adventure.', "three-men-in-a-boat.jpg", 10),
('Uno Cards', 1150, 60, 'Mattel', 22, "The world's most beloved card game.", "uno.png", 11);

SELECT * FROM product;

INSERT INTO deliveryAgent (first_name, last_name, availabilityStatus, phone_no, email, password, dob, age) VALUES
("Muhammad", "Sharif", "Offline", 3001234567, "sharif@quickcart.pk", "Sharif@123#", "1995-05-01", 22),
("Ahmed", "Abbas", "Available", 3021234568, "ahmed.abbas@quickcart.pk", "Ahmed@123!", "1994-11-11", 23),
("Hassan", "Malik", "Offline", 3051234569, "hassan.malik@quickcart.pk", "Hassan@123&", "1995-05-01", 22),
("Ali", "Khan", "Available", 3111234570, "ali.khan@quickcart.pk", "Ali@123", "1998-02-14", 25),
("Farooq", "Ahmed", "Offline", 3411234571, "farooq.ahmed@quickcart.pk", "Farooq@123", "1989-12-01", 20),
("Bilal", "Hassan", "Available", 3091234572, "bilal.hassan@quickcart.pk", "Bilal@123", "2002-05-23", 32),
("Irfan", "Aziz", "Offline", 3001234573, "irfan.aziz@quickcart.pk", "Irfan@123", "1985-06-01", 23),
("Karim", "Rauf", "Available", 3021234574, "karim.rauf@quickcart.pk", "Karim@123", "1987-05-01", 28),
("Nasir", "Ibrahim", "Offline", 3051234575, "nasir.ibrahim@quickcart.pk", "Nasir@123", "2000-11-01", 18),
("Samir", "Hussain", "Available", 3111234576, "samir.hussain@quickcart.pk", "Samir@123", "1985-01-21", 42);

UPDATE deliveryAgent
SET age = DATEDIFF(CURDATE(), dob) / 365;

SELECT * FROM deliveryAgent;

INSERT INTO store (address_street, address_city, address_state, pincode) VALUES 
('Street 45, Saddar', 'Karachi', 'Sindh', 74000),
('Mall Road Branch', 'Lahore', 'Punjab', 54000),
('Blue Area Branch', 'Islamabad', 'Islamabad', 44000),
('Rawalpindi Hub', 'Rawalpindi', 'Punjab', 46000);

SELECT * FROM store;

INSERT INTO `order` (status, total_price, time, location, agentPayment, customerID, agentID) VALUES
('Delivered', 2500, '2023-07-15 08:45:00', "Street 45, Fort, Karachi, Sindh", 150, 1, 5),
('Delivered', 3200, '2023-07-16 09:30:00', "Mall Road 78, Lahore, Punjab", 150, 2, 7),
('Delivered', 5150, '2023-07-17 10:15:00', "Sector 7, Blue Area, Islamabad", 150, 3, 3),
('Delivered', 4100, '2023-07-18 11:00:00', "Subzi Mandi, Rawalpindi, Punjab", 150, 4, 8),
('Delivered', 5500, '2023-07-19 12:30:00', "Chaman Gate, Multan, Punjab", 200, 5, 2),
('Delivered', 2600, '2023-07-20 13:45:00', "Railway Road 34, Faisalabad, Punjab", 150, 6, 6),
('Delivered', 3800, '2023-07-21 14:30:00', "Cantonment Road, Peshawar, KP", 200, 7, 10),
('Delivered', 8500, '2023-07-22 15:15:00', "Zarghoon Road, Quetta, Balochistan", 200, 8, 4),
('Delivered', 2900, '2023-07-23 16:00:00', "Beach Road 56, Karachi, Sindh", 200, 9, 1),
('Delivered', 4000, '2023-07-24 17:45:00', "Clifton Avenue, Karachi, Sindh", 150, 10, 9),
('Delivered', 3200, '2023-08-05 09:30:00', "F-Block, Islamabad", 150, 26, 4),
('Delivered', 3500, '2023-08-06 10:15:00', "Defence Road, Lahore, Punjab", 150, 27, 8),
('Delivered', 5800, '2023-08-07 11:00:00', "New Garden Road, Lahore, Punjab", 200, 28, 6),
('Delivered', 4200, '2023-08-08 12:45:00', "Canal Road 23, Lahore, Punjab", 150, 29, 2),
('Delivered', 5300, '2023-08-09 13:30:00', "Tariq Road 45, Karachi, Sindh", 150, 30, 1),
('Delivered', 2800, '2023-08-10 14:15:00', "Parkway Road, Islamabad", 150, 31, 9),
('Delivered', 4500, '2023-08-11 15:00:00', "Gulshan-e-Iqbal, Karachi, Sindh", 200, 32, 5),
('Delivered', 6200, '2023-08-12 15:45:00', "Data Gunj Baksh, Lahore, Punjab", 200, 33, 7),
('Delivered', 3400, '2023-08-13 16:30:00', "Abbasi Shaheed, Rawalpindi, Punjab", 200, 34, 10),
('Delivered', 4200, '2023-08-14 17:15:00', "Gulistan-e-Johar, Karachi, Sindh", 200, 35, 3),
('Delivered', 4800, '2023-09-01 09:30:00', "Liberty Market, Lahore, Punjab", 200, 36, 5),
('Delivered', 5200, '2023-09-02 10:15:00', "Scheme 33, Karachi, Sindh", 200, 37, 8),
('Delivered', 6500, '2023-09-03 11:00:00', "Saddar, Rawalpindi, Punjab", 200, 38, 6),
('Delivered', 4800, '2023-09-04 12:45:00', "Mall of Lahore, Lahore, Punjab", 200, 39, 2),
('Delivered', 5900, '2023-09-05 13:30:00', "Nazimabad, Karachi, Sindh", 200, 40, 1),
('Delivered', 3200, '2023-09-06 14:15:00', "G-9 Markaz, Islamabad", 150, 41, 9),
('Delivered', 4700, '2023-09-07 15:00:00', "Township 4, Lahore, Punjab", 200, 42, 5),
('Delivered', 6100, '2023-09-08 15:45:00', "Civic Centre, Karachi, Sindh", 150, 43, 7),
('Delivered', 3600, '2023-09-09 16:30:00', "Johar Town, Lahore, Punjab", 200, 44, 10),
('Delivered', 4800, '2023-09-10 17:15:00', "Chaklala, Rawalpindi, Punjab", 200, 46, 3),
('Delivered', 5500, '2023-09-11 18:00:00', "Abbottabad Road, Rawalpindi, Punjab", 150, 47, 6),
('Delivered', 4200, '2023-09-12 18:45:00', "Gulshan, Karachi, Sindh", 150, 48, 4),
('Delivered', 5400, '2023-09-13 19:30:00', "Park Road 12, Lahore, Punjab", 150, 48, 2),
('Delivered', 3200, '2023-09-14 20:15:00', "Thokar, Multan, Punjab", 150, 49, 8),
('Delivered', 4600, '2023-09-15 21:00:00', "Pir Waleed Road, Lahore, Punjab", 150, 50, 7),
('Delivered', 6800, '2023-09-16 21:45:00', "DHA, Lahore, Punjab", 200, 1, 9),
('Delivered', 4200, '2023-09-17 22:30:00', "North Nazimabad, Karachi, Sindh", 200, 2, 10),
('Delivered', 4500, '2023-09-18 23:15:00', "Shahdara, Lahore, Punjab", 150, 3, 4),
('Delivered', 5800, '2023-09-19 00:00:00', "Wapda Town, Lahore, Punjab", 150, 4, 6),
('Delivered', 4700, '2023-09-20 00:45:00', "Askari, Rawalpindi, Punjab", 200, 5, 3),
('Delivered', 5900, '2023-09-21 01:30:00', "Ghazni Street, Lahore, Punjab", 200, 6, 8),
('Delivered', 3200, '2023-09-22 02:15:00', "Shaheen, Islamabad", 150, 7, 1),
('Delivered', 4300, '2023-09-23 03:00:00', "Muzaffarabad, AJK", 150, 8, 2),
('Delivered', 6200, '2023-09-24 03:45:00', "Khayaban-e-Bahria, Karachi, Sindh", 200, 9, 5),
('Delivered', 3800, '2023-09-25 04:30:00', "Allama Iqbal, Lahore, Punjab", 200, 10, 9),
('Delivered', 4200, '2023-09-26 05:15:00', "Garden Road, Faisalabad, Punjab", 200, 11, 7),
('Delivered', 5500, '2023-09-27 06:00:00', "Mozang, Lahore, Punjab", 150, 12, 3),
('Delivered', 4800, '2023-09-28 06:45:00', "Sargodha Road, Sargodha, Punjab", 150, 13, 6),
('Delivered', 5600, '2023-09-29 07:30:00', "Jauharabad, Jhang, Punjab", 150, 14, 4),
('Delivered', 3400, '2023-09-30 08:15:00', "Main Street, Multan, Punjab", 150, 15, 10),
('Delivered', 5200, '2023-10-01 09:00:00', "Business District, Lahore, Punjab", 200, 16, 8),
('Delivered', 6400, '2023-10-02 09:45:00', "Commercial Road, Karachi, Sindh", 150, 17, 5),
('Delivered', 4100, '2023-10-03 10:30:00', "Industrial Area, Faisalabad, Punjab", 200, 18, 3),
('Delivered', 4800, '2023-10-04 11:15:00', "Trade Centre, Islamabad", 200, 19, 1),
('Delivered', 5600, '2023-10-05 12:00:00', "Market Street, Rawalpindi, Punjab", 200, 20, 9);

SELECT * FROM `order`;

INSERT INTO wallet (customerID, balance, upiID, rewardPoints) VALUES
(1, 5000, 'customer1@easypaisa.pk', 0),
(2, 7500, 'customer2@easypaisa.pk', 0),
(3, 1000, 'customer3@easypaisa.pk', 0),
(4, 2150, 'customer4@easypaisa.pk', 0),
(5, 1100, 'customer5@easypaisa.pk', 0),
(6, 5000, 'customer6@easypaisa.pk', 0),
(7, 2350, 'customer7@easypaisa.pk', 0),
(8, 2750, 'customer8@easypaisa.pk', 0),
(9, 3000, 'customer9@easypaisa.pk', 0),
(10, 50000, 'customer10@easypaisa.pk', 0),
(11, 5000, 'customer11@easypaisa.pk', 0),
(12, 7500, 'customer12@easypaisa.pk', 0),
(13, 1000, 'customer13@easypaisa.pk', 0),
(14, 2150, 'customer14@easypaisa.pk', 0),
(15, 1100, 'customer15@easypaisa.pk', 0),
(16, 5000, 'customer16@easypaisa.pk', 0),
(17, 2350, 'customer17@easypaisa.pk', 0),
(18, 2750, 'customer18@easypaisa.pk', 0),
(19, 3000, 'customer19@easypaisa.pk', 0),
(20, 50000, 'customer20@easypaisa.pk', 0),
(21, 5000, 'customer21@easypaisa.pk', 0),
(22, 7500, 'customer22@easypaisa.pk', 0),
(23, 1000, 'customer23@easypaisa.pk', 0),
(24, 2150, 'customer24@easypaisa.pk', 0),
(25, 1100, 'customer25@easypaisa.pk', 0),
(26, 5000, 'customer26@easypaisa.pk', 0),
(27, 2350, 'customer27@easypaisa.pk', 0),
(28, 2750, 'customer28@easypaisa.pk', 0),
(29, 3000, 'customer29@easypaisa.pk', 0),
(30, 50000, 'customer30@easypaisa.pk', 0),
(31, 5000, 'customer31@easypaisa.pk', 0),
(32, 7500, 'customer32@easypaisa.pk', 0),
(33, 1000, 'customer33@easypaisa.pk', 0),
(34, 2150, 'customer34@easypaisa.pk', 0),
(35, 1100, 'customer35@easypaisa.pk', 0),
(36, 5000, 'customer36@easypaisa.pk', 0),
(37, 2350, 'customer37@easypaisa.pk', 0),
(38, 2750, 'customer38@easypaisa.pk', 0),
(39, 3000, 'customer39@easypaisa.pk', 0),
(40, 50000, 'customer40@easypaisa.pk', 0),
(41, 5000, 'customer41@easypaisa.pk', 0),
(42, 7500, 'customer42@easypaisa.pk', 0),
(43, 1000, 'customer43@easypaisa.pk', 0),
(44, 2150, 'customer44@easypaisa.pk', 0),
(45, 1100, 'customer45@easypaisa.pk', 0),
(46, 5000, 'customer46@easypaisa.pk', 0),
(47, 2350, 'customer47@easypaisa.pk', 0),
(48, 2750, 'customer48@easypaisa.pk', 0),
(49, 3000, 'customer49@easypaisa.pk', 0),
(50, 50000, 'customer50@easypaisa.pk', 0);

SELECT * FROM wallet;

INSERT INTO delivery_agent_wallet (agentID, earning_balance, earning_paid, earning_total, Transaction_history, upiID) VALUES
(1, 0.00, 0.00, 0.00, '12-01-2024^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent1@easypaisa.pk'),
(2, 0.00, 0.00, 0.00, '25-09-2023^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent2@easypaisa.pk'),
(3, 0.00, 0.00, 0.00, '21-03-2023^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent3@easypaisa.pk'),
(4, 0.00, 0.00, 0.00, '21-12-2023^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent4@easypaisa.pk'),
(5, 0.00, 0.00, 0.00, '05-11-2023^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent5@easypaisa.pk'),
(6, 0.00, 0.00, 0.00, '21-02-2024^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent6@easypaisa.pk'),
(7, 0.00, 0.00, 0.00, '17-10-2023^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent7@easypaisa.pk'),
(8, 0.00, 0.00, 0.00, '17-08-2023^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent8@easypaisa.pk'),
(9, 0.00, 0.00, 0.00, '21-03-2024^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent9@easypaisa.pk'),
(10, 0.00, 0.00, 0.00, '25-03-2024^ You Created QuickCart Wallet Account!^ 0.00^ 0.00^ 0.00', 'agent10@easypaisa.pk');
    
SELECT * FROM delivery_agent_wallet;

INSERT INTO ProductReview (productReviewID, orderID, customerID, comment, rating) VALUES
(1, 1, 1, 'Great product!', 5),
(2, 2, 2, 'Excellent service!', 4),
(3, 3, 3, 'Fast shipping, good quality.', 5),
(4, 4, 4, 'Satisfied with the purchase.', 3),
(5, 5, 5, 'Amazing product, highly recommended.', 5),
(6, 6, 6, 'Packaging could be better.', 3),
(7, 7, 7, 'Impressed with the customer service.', 4),
(8, 8, 8, 'Not happy with the delivery time.', 2),
(9, 9, 9, 'Product as described.', 4),
(10, 10, 10, 'Easy returns process.', 5);

SELECT * FROM ProductReview;

INSERT INTO DeliveryReview (deliveryReviewID, orderID, agentID, comment, rating, tip) VALUES
(1, 1, 5, 'Delivery was on time.', 5, 0.00),
(2, 2, 7, 'Polite delivery agent.', 4, 0.00),
(3, 3, 3, 'Quick and efficient delivery.', 5, 0.00),
(4, 4, 8, 'Delayed delivery, but agent was apologetic.', 3, 0.00),
(5, 5, 2, 'Excellent service, received with a smile.', 5, 0.00),
(6, 6, 6, 'Delivery agent could be more professional.', 3, 0.00),
(7, 7, 10, 'Agent went above and beyond to deliver.', 4, 0.00),
(8, 8, 4, 'Late delivery, no communication from agent.', 2, 0.00),
(9, 9, 1, 'Smooth delivery process.', 4, 0.00),
(10, 10, 9, 'Agent was helpful in setting up the product.', 5, 0.00),
(11, 17, 5, 'Smooth delivery process.', 4, 0.00),
(12, 15, 1, 'Excellent service, received with a smile.', 5, 0.00),
(13, 29, 10, 'Delayed delivery, but agent was apologetic.', 3, 0.00),
(14, 12, 8, 'Agent went above and beyond to deliver.', 4, 0.00),
(15, 18, 7, 'Delivery agent could be more professional.', 3, 0.00),
(16, 20, 3, 'Polite delivery agent.', 4, 0.00),
(17, 11, 4, 'Late delivery, no communication from agent.', 2, 0.00),
(18, 25, 1, 'Smooth delivery process.', 4, 0.00),
(19, 23, 6, 'Late delivery, no communication from agent.', 2, 0.00),
(20, 27, 5, 'Excellent service, received with a smile.', 5, 0.00),
(21, 33, 2, 'Polite delivery agent.', 4, 0.00),
(22, 14, 2, 'Smooth delivery process.', 4, 0.00),
(23, 21, 5, 'Delayed delivery, but agent was apologetic.', 3, 0.00),
(24, 16, 9, 'Agent went above and beyond to deliver.', 4, 0.00),
(25, 22, 8, 'Polite delivery agent.', 4, 0.00),
(26, 28, 7, 'Delayed delivery, but agent was apologetic.', 3, 0.00),
(27, 35, 7, 'Late delivery, no communication from agent.', 2, 0.00),
(28, 32, 4, 'Excellent service, received with a smile.', 5, 0.00),
(29, 30, 3, 'Smooth delivery process.', 4, 0.00),
(30, 13, 6, 'Late delivery, no communication from agent.', 2, 0.00),
(31, 24, 2, 'Smooth delivery process.', 4, 0.00),
(32, 37, 10, 'Polite delivery agent.', 4, 0.00),
(33, 19, 10, 'Delayed delivery, but agent was apologetic.', 3, 0.00),
(34, 26, 9, 'Agent went above and beyond to deliver.', 4, 0.00),
(35, 40, 3, 'Delivery agent could be more professional.', 3, 0.00),
(36, 39, 6, 'Polite delivery agent.', 4, 0.00),
(37, 43, 2, 'Late delivery, no communication from agent.', 2, 0.00),
(38, 31, 6, 'Smooth delivery process.', 4, 0.00),
(39, 34, 8, 'Late delivery, no communication from agent.', 2, 0.00),
(40, 38, 4, 'Excellent service, received with a smile.', 5, 0.00);

SELECT * FROM DeliveryReview;

INSERT INTO addsToCart (customerID, productID, quantity) VALUES
(1, 1, 2),
(1, 2, 1),
(2, 3, 3),
(2, 4, 1),
(3, 1, 1),
(3, 5, 2),
(4, 6, 1),
(5, 7, 4),
(6, 8, 2),
(7, 9, 1);

SELECT * FROM addsToCart;

INSERT INTO orderConsistsProduct (orderID, productID, quantity) VALUES
(1, 1, 2), 
(1, 2, 1), 
(1, 6, 3),
(1, 10, 4),
(1, 12, 2),
(2, 3, 3), 
(2, 4, 1), 
(2, 11, 3),
(2, 13, 4),
(3, 1, 1), 
(3, 2, 2),
(3, 3, 5),
(3, 5, 2), 
(4, 5, 1),
(4, 6, 1), 
(4, 8, 5),
(4, 12, 3),
(5, 1, 4),
(5, 7, 4), 
(5, 8, 1),
(6, 1, 2),
(6, 8, 2),
(6, 10, 1),
(7, 3, 1),
(7, 10, 2),
(7, 11, 4),
(8, 2, 1),
(8, 7, 2),
(8, 10, 2),
(9, 5, 1),
(9, 12, 1),
(9, 13, 3),
(10, 3, 1),
(10, 4, 3),
(11, 5, 2),
(11, 7, 2),
(12, 8, 4),
(12, 12, 4),
(13, 4, 3),
(13, 10, 2),
(14, 5, 5),
(14, 11, 3),
(15, 8, 1),
(15, 13, 1),
(16, 10, 7);

SELECT * FROM orderConsistsProduct;

INSERT INTO storecontainsproduct (storeID, productID, quantity) VALUES
(1, 1, 10),  
(2, 2, 20),  
(1, 3, 15),  
(4, 4, 12),
(2, 5, 10),
(3, 6, 22),
(2, 7, 32),
(4, 8, 21),
(4, 9, 15),
(4, 1, 17);  

SELECT * FROM  storecontainsproduct;    

