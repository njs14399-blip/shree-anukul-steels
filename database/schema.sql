-- Shree Anukul Steels - SQLite Database Schema

-- Admin Users
CREATE TABLE IF NOT EXISTS admin_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    email TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Website Settings
CREATE TABLE IF NOT EXISTS website_settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key TEXT NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- SEO Settings
CREATE TABLE IF NOT EXISTS seo_settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_name TEXT NOT NULL UNIQUE,
    meta_title TEXT,
    meta_description TEXT,
    meta_keywords TEXT,
    og_title TEXT,
    og_description TEXT,
    og_image TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Product Categories
CREATE TABLE IF NOT EXISTS product_categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    description TEXT,
    sort_order INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Products
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    short_description TEXT,
    description TEXT,
    specifications TEXT,
    features TEXT,
    image TEXT,
    status INTEGER DEFAULT 1,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id)
);

-- Product Images
CREATE TABLE IF NOT EXISTS product_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    image_path TEXT NOT NULL,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Project Categories
CREATE TABLE IF NOT EXISTS project_categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Projects
CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    location TEXT,
    description TEXT,
    steel_quantity TEXT,
    completion_year TEXT,
    image TEXT,
    status INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES project_categories(id)
);

-- Testimonials
CREATE TABLE IF NOT EXISTS testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    company_name TEXT,
    rating INTEGER DEFAULT 5,
    review_text TEXT NOT NULL,
    image TEXT,
    status INTEGER DEFAULT 1,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Leads
CREATE TABLE IF NOT EXISTS leads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    phone TEXT,
    email TEXT,
    city TEXT,
    requirement TEXT,
    lead_source TEXT DEFAULT 'Website',
    status TEXT DEFAULT 'New Lead',
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Lead Reminders
CREATE TABLE IF NOT EXISTS lead_reminders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    lead_id INTEGER NOT NULL,
    reminder_date DATE NOT NULL,
    reminder_note TEXT,
    is_completed INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
);

-- Contact Enquiries
CREATE TABLE IF NOT EXISTS enquiries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT,
    phone TEXT,
    subject TEXT,
    message TEXT,
    status TEXT DEFAULT 'New',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Quotations
CREATE TABLE IF NOT EXISTS quotations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    customer_phone TEXT,
    customer_email TEXT,
    product TEXT,
    quantity TEXT,
    price REAL DEFAULT 0,
    transport_charges REAL DEFAULT 0,
    total_amount REAL DEFAULT 0,
    notes TEXT,
    status TEXT DEFAULT 'Draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Quote Request (from frontend)
CREATE TABLE IF NOT EXISTS quote_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT,
    phone TEXT,
    product_name TEXT,
    quantity TEXT,
    message TEXT,
    status TEXT DEFAULT 'New',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: 12345678 hashed with PHP password_hash)
-- Password hash will be generated during setup

-- Insert default website settings
INSERT OR IGNORE INTO website_settings (setting_key, setting_value) VALUES
('site_name', 'Shree Anukul Steels'),
('phone', '+91 8981040333'),
('email', 'contact@shreeanukulsteels.com'),
('address', 'Unit 3, 2nd Floor, Mahabir Highrise, 356 Canal St, Sreebhumi, Lake Town, Kolkata - 700048, West Bengal, India'),
('whatsapp', '918981040333'),
('facebook', ''),
('twitter', ''),
('linkedin', ''),
('instagram', ''),
('youtube', ''),
('google_analytics', ''),
('google_search_console', ''),
('recaptcha_site_key', ''),
('recaptcha_secret_key', '');

-- Insert default SEO settings
INSERT OR IGNORE INTO seo_settings (page_name, meta_title, meta_description, meta_keywords) VALUES
('home', 'Shree Anukul Steels - Premium TMT Steel Solutions | Steel Supplier India', 'Shree Anukul Steels is a leading steel supplier in India since 1975. We supply TMT Bars, Steel Pipes, Steel Plates, and more to builders and construction companies.', 'steel supplier india, tmt bars supplier, steel trader india, construction steel supplier, steel distributor india, steel supplier kolkata, steel supplier west bengal'),
('about', 'About Shree Anukul Steels - Industry Leaders Since 1975', 'Learn about Shree Anukul Steels, a trusted steel supplier since 1975. BIS certified, GEM registered trader with advanced quality control and reliable delivery network.', 'about shree anukul steels, steel company india, steel supplier history, bis certified steel'),
('products', 'Steel Products - TMT Bars, Steel Pipes, Plates & More | Shree Anukul Steels', 'Browse our complete range of steel products including TMT Bars, Steel Angles, Steel Pipes, Steel Plates, MS Beams, and more. Get quotes from Shree Anukul Steels.', 'tmt bars, steel pipes, steel plates, steel angles, ms beam, ms channel, steel products india'),
('projects', 'Our Projects - Construction Projects | Shree Anukul Steels', 'View construction projects completed using steel supplied by Shree Anukul Steels. Residential, commercial, industrial and infrastructure projects across India.', 'construction projects india, steel supply projects, residential construction, commercial construction'),
('contact', 'Contact Shree Anukul Steels - Get in Touch', 'Contact Shree Anukul Steels for steel product enquiries and quotations. Located in Kolkata, West Bengal. Call +91 8981040333.', 'contact shree anukul steels, steel supplier contact, steel enquiry kolkata');

-- Insert default product categories
INSERT OR IGNORE INTO product_categories (name, slug, sort_order) VALUES
('TMT Bars', 'tmt-bars', 1),
('Steel Angles', 'steel-angles', 2),
('Steel Pipes', 'steel-pipes', 3),
('Steel Plates', 'steel-plates', 4),
('Black Wire', 'black-wire', 5),
('HB Wire', 'hb-wire', 6),
('Mild Steel Bars', 'mild-steel-bars', 7),
('MS Beam', 'ms-beam', 8),
('MS Channel', 'ms-channel', 9),
('MS Coil', 'ms-coil', 10),
('MS Flat', 'ms-flat', 11),
('MS Nails', 'ms-nails', 12);

-- Insert default products
INSERT OR IGNORE INTO products (category_id, name, slug, short_description, description, specifications, features, image) VALUES
(1, 'TMT Bars', 'tmt-bars', 'High-strength TMT reinforcement bars for construction', 'Premium quality TMT (Thermo Mechanically Treated) bars manufactured with advanced technology. Ideal for all types of construction projects including residential, commercial, and industrial buildings.', 'Grade: Fe-500D, Fe-550D|Diameter: 8mm to 32mm|Length: 12 meters standard|Standards: IS 1786:2008', 'High tensile strength|Excellent bendability|Superior weldability|Corrosion resistant|Earthquake resistant', 'products/tmt-bars.jpg'),
(2, 'Steel Angles', 'steel-angles', 'Structural steel angles for frameworks and supports', 'High quality structural steel angles used in building frameworks, supports, and various construction applications. Available in multiple sizes and grades.', 'Grade: IS 2062|Size: 25x25mm to 200x200mm|Thickness: 3mm to 20mm|Length: 6m / 12m', 'High structural strength|Versatile applications|Precise dimensions|Durable finish', 'products/steel-angles.jpg'),
(3, 'Steel Pipes', 'steel-pipes', 'Durable steel pipes for plumbing and structural use', 'Premium steel pipes suitable for plumbing, structural, and industrial applications. Available in various diameters and wall thicknesses.', 'Type: ERW / Seamless|Diameter: 15mm to 300mm|Thickness: 1.5mm to 12mm|Standards: IS 1239, IS 3589', 'Corrosion resistant coating|High pressure tolerance|Uniform wall thickness|Long service life', 'products/steel-pipes.jpg'),
(4, 'Steel Plates', 'steel-plates', 'Heavy-duty steel plates for industrial applications', 'Industrial grade steel plates for heavy construction, shipbuilding, and manufacturing applications. Available in various thicknesses and grades.', 'Grade: IS 2062, SA 516|Thickness: 5mm to 100mm|Width: Up to 2500mm|Length: Up to 12000mm', 'High tensile strength|Excellent machinability|Uniform thickness|Superior surface finish', 'products/steel-plates.jpg'),
(5, 'Black Wire', 'black-wire', 'Binding wire for construction reinforcement', 'High quality black annealed binding wire used for tying reinforcement bars in construction. Soft and flexible for easy handling.', 'Gauge: 18 to 22 SWG|Material: Mild Steel|Finish: Black annealed|Packaging: Coils', 'Highly flexible|Easy to work with|Strong binding strength|Cost effective', 'products/black-wire.jpg'),
(6, 'HB Wire', 'hb-wire', 'Hard bright wire for industrial applications', 'Hard bright steel wire used in various industrial applications including fencing, mesh making, and manufacturing.', 'Gauge: 8 to 20 SWG|Material: High carbon steel|Finish: Bright|Tensile: High', 'High tensile strength|Bright finish|Uniform diameter|Versatile usage', 'products/hb-wire.jpg'),
(7, 'Mild Steel Bars', 'mild-steel-bars', 'MS round bars for general fabrication', 'Quality mild steel round bars used in general fabrication, manufacturing, and construction applications.', 'Grade: IS 2062|Diameter: 6mm to 100mm|Length: 6m standard|Shape: Round', 'Good weldability|Easy machining|Versatile applications|Consistent quality', 'products/mild-steel-bars.jpg'),
(8, 'MS Beam', 'ms-beam', 'Structural MS beams for building construction', 'Heavy-duty mild steel beams used as primary structural members in building construction and infrastructure projects.', 'Type: ISMB / ISWB|Size: 100mm to 600mm|Grade: IS 2062|Length: 12m standard', 'High load bearing capacity|Structural stability|Precise dimensions|Long lasting', 'products/ms-beam.jpg'),
(9, 'MS Channel', 'ms-channel', 'MS channels for structural frameworks', 'Mild steel channels used in structural frameworks, supports, and various industrial applications.', 'Type: ISMC / ISLC|Size: 75mm to 400mm|Grade: IS 2062|Length: 6m / 12m', 'Strong structural support|Versatile usage|Standard dimensions|Durable', 'products/ms-channel.jpg'),
(10, 'MS Coil', 'ms-coil', 'Hot rolled MS coils for manufacturing', 'Hot rolled mild steel coils used in manufacturing, fabrication, and various industrial applications.', 'Grade: IS 2062|Thickness: 1.6mm to 12mm|Width: Up to 1500mm|Type: Hot Rolled', 'Uniform thickness|Smooth surface|High formability|Cost effective', 'products/ms-coil.jpg'),
(11, 'MS Flat', 'ms-flat', 'Mild steel flats for fabrication work', 'Quality mild steel flat bars used in fabrication, grills, gates, and various construction applications.', 'Grade: IS 2062|Width: 20mm to 200mm|Thickness: 3mm to 25mm|Length: 6m standard', 'Smooth finish|Easy to cut and weld|Precise dimensions|Multiple sizes available', 'products/ms-flat.jpg'),
(12, 'MS Nails', 'ms-nails', 'Construction nails for woodwork and building', 'Premium quality mild steel nails for construction, woodwork, and general building applications.', 'Size: 1 inch to 6 inch|Material: Mild Steel|Finish: Bright / Galvanized|Head: Flat', 'Sharp point for easy driving|Strong holding power|Rust resistant options|Various sizes', 'products/ms-nails.jpg');

-- Insert default project categories
INSERT OR IGNORE INTO project_categories (name, slug) VALUES
('Residential', 'residential'),
('Commercial', 'commercial'),
('Industrial', 'industrial'),
('Infrastructure', 'infrastructure');

-- Insert sample projects
INSERT OR IGNORE INTO projects (category_id, name, slug, location, description, steel_quantity, completion_year, image) VALUES
(2, 'Kolkata Commercial Complex', 'kolkata-commercial-complex', 'Kolkata, West Bengal', 'A state-of-the-art commercial complex built with premium steel supplied by Shree Anukul Steels. The project showcases our commitment to quality and timely delivery.', '2500 Tons', '2023', 'projects/kolkata-commercial.jpg'),
(1, 'Durgapur Residential Tower', 'durgapur-residential-tower', 'Durgapur, West Bengal', 'Modern residential tower project in Durgapur featuring high-quality TMT bars and structural steel from Shree Anukul Steels.', '1800 Tons', '2022', 'projects/durgapur-residential.jpg'),
(2, 'Imperial Mall Silchar', 'imperial-mall-silchar', 'Silchar, Assam', 'A premium shopping mall constructed using top-grade steel materials supplied by Shree Anukul Steels to ensure structural integrity and safety.', '3200 Tons', '2024', 'projects/imperial-mall.jpg'),
(3, 'Guwahati Industrial Park', 'guwahati-industrial-park', 'Guwahati, Assam', 'Large-scale industrial park development in Guwahati featuring heavy structural steel and specialized industrial materials.', '5000 Tons', '2023', 'projects/guwahati-industrial.jpg'),
(4, 'NH Highway Bridge Project', 'nh-highway-bridge', 'Siliguri, West Bengal', 'Critical infrastructure project involving highway bridge construction with high-grade steel for maximum durability and safety.', '4200 Tons', '2024', 'projects/highway-bridge.jpg'),
(1, 'Lake Town Apartments', 'lake-town-apartments', 'Kolkata, West Bengal', 'Premium residential apartment complex in Lake Town built with BIS certified steel products from Shree Anukul Steels.', '1200 Tons', '2023', 'projects/lake-town-apartments.jpg');

-- Insert sample testimonials
INSERT OR IGNORE INTO testimonials (customer_name, company_name, rating, review_text) VALUES
('Rajesh Kumar', 'Kumar Construction Pvt Ltd', 5, 'Shree Anukul Steels has been our trusted steel supplier for over 10 years. Their TMT bars are of excellent quality and delivery is always on time. Highly recommended for any construction project.'),
('Amit Sharma', 'Sharma Builders', 5, 'We have been sourcing steel from Shree Anukul Steels for our residential projects. The quality of products and customer service is outstanding. They offer competitive prices and reliable delivery.'),
('Priya Devi', 'Devi Infrastructure', 4, 'Excellent steel supplier with a wide range of products. Their team is very professional and helpful in selecting the right materials for our projects. Great experience working with them.'),
('Suresh Patel', 'Patel & Associates', 5, 'Best steel supplier in Kolkata. Their BIS certified products give us confidence in the quality of materials we use in our construction projects. Timely delivery and competitive pricing.'),
('Anil Gupta', 'Gupta Real Estate', 5, 'Shree Anukul Steels provides premium quality steel at the best prices. Their customer support is exceptional and they always go the extra mile to meet our requirements.');
