<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$resume_data = [
    'name'          => 'Arpit Sharma',
    'email'         => 'avi6117sharma@gmail.com',
    'phone'         => '9971027280',
    'address'       => 'DRDO Complex, B1',
    'summary'       => 'Computer Science student at JUIT with a strong foundation in programming and problem-solving. Passionate about software development, AI, and open-source contributions.',
    'education'     => [
        ['institution' => 'Jaypee University of Information Technology (JUIT)', 'degree' => 'B.Tech in CSE', 'year' => '2023-2027'],
        ['institution' => 'KV Sector 8', 'degree' => '12th (Science)', 'year' => '2020-2022']
    ],
    'skills'        => ['Python', 'Java', 'HTML/CSS', 'Git', 'Problem-Solving'],
    'projects'      => [
        ['title' => 'Resume Builder', 'description' => 'A web app to generate professional resumes using PHP/HTML.']
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Resume | <?php echo htmlspecialchars($resume_data['name']); ?></title>
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2980b9;
            --dark: #2c3e50;
            --light: #ecf0f1;
            --accent: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 30px;
            color: #333;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--primary);
        }
        
        h1 {
            color: var(--dark);
            margin: 0;
            font-size: 2.2rem;
        }
        
        h2 {
            color: var(--secondary);
            font-size: 1.4rem;
            margin: 25px 0 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }
        
        .contact-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .contact-info i {
            color: var(--primary);
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .education-item, .project-item {
            margin-bottom: 15px;
        }
        
        .institution, .project-title {
            font-weight: 600;
            color: var(--dark);
        }
        
        .degree, .project-description {
            margin-left: 15px;
        }
        
        .year {
            float: right;
            color: #666;
        }
        
        ul.skills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0;
            list-style: none;
        }
        
        ul.skills li {
            background-color: var(--light);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .download-btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .download-btn:hover {
            background-color: var(--secondary);
        }
        
        @media print {
            body {
                box-shadow: none;
                padding: 0;
            }
            
            .download-btn {
                display: none;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="header">
        <h1><?php echo htmlspecialchars($resume_data['name']); ?></h1>
    </div>
    
    <div class="contact-info">
        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($resume_data['email']); ?></span>
        <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($resume_data['phone']); ?></span>
        <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($resume_data['address']); ?></span>
    </div>

    <!-- Summary -->
    <div class="section">
        <h2>Summary</h2>
        <p><?php echo htmlspecialchars($resume_data['summary']); ?></p>
    </div>

    <!-- Education -->
    <div class="section">
        <h2>Education</h2>
        <?php foreach ($resume_data['education'] as $edu): ?>
            <div class="education-item">
                <div class="institution"><?php echo htmlspecialchars($edu['institution']); ?></div>
                <div class="degree"><?php echo htmlspecialchars($edu['degree']); ?> <span class="year"><?php echo htmlspecialchars($edu['year']); ?></span></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Skills -->
    <div class="section">
        <h2>Skills</h2>
        <ul class="skills">
            <?php foreach ($resume_data['skills'] as $skill): ?>
                <li><?php echo htmlspecialchars($skill); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Projects -->
    <div class="section">
        <h2>Projects</h2>
        <?php foreach ($resume_data['projects'] as $project): ?>
            <div class="project-item">
                <div class="project-title"><?php echo htmlspecialchars($project['title']); ?></div>
                <div class="project-description"><?php echo htmlspecialchars($project['description']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <form action="generate_pdf.php" method="post">
        <button type="submit" class="download-btn">
            <i class="fas fa-download"></i> Download PDF
        </button>
    </form>
</body>
</html>