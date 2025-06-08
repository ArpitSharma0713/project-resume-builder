<div class="resume classic">
    <header class="resume-header">
        <h1><?= $resume['personal_info']['full_name'] ?? '' ?></h1>
        <div class="contact-info">
            <?php if (!empty($resume['personal_info']['email'])): ?>
                <span><?= $resume['personal_info']['email'] ?></span>
            <?php endif; ?>
            
            <?php if (!empty($resume['personal_info']['phone'])): ?>
                <span><?= $resume['personal_info']['phone'] ?></span>
            <?php endif; ?>
            
            <?php if (!empty($resume['personal_info']['address'])): ?>
                <span><?= $resume['personal_info']['address'] ?></span>
            <?php endif; ?>
        </div>
    </header>
    
    <?php if (!empty($resume['personal_info']['summary'])): ?>
        <section class="summary">
            <h2>Summary</h2>
            <p><?= $resume['personal_info']['summary'] ?></p>
        </section>
    <?php endif; ?>
    
    <?php if (!empty($resume['education'])): ?>
        <section class="education">
            <h2>Education</h2>
            <?php foreach ($resume['education'] as $edu): ?>
                <div class="education-item">
                    <h3><?= $edu['institution'] ?></h3>
                    <div class="details">
                        <span class="degree"><?= $edu['degree'] ?></span>
                        <?php if (!empty($edu['field'])): ?>
                            <span class="field">in <?= $edu['field'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="dates">
                        <?php if (!empty($edu['start_date'])): ?>
                            <span><?= date('M Y', strtotime($edu['start_date'])) ?></span> -
                        <?php endif; ?>
                        <?php if (!empty($edu['end_date'])): ?>
                            <span><?= date('M Y', strtotime($edu['end_date'])) ?></span>
                        <?php else: ?>
                            <span>Present</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($edu['description'])): ?>
                        <p class="description"><?= $edu['description'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
    
    <?php if (!empty($resume['experience'])): ?>
        <section class="experience">
            <h2>Experience</h2>
            <?php foreach ($resume['experience'] as $exp): ?>
                <div class="experience-item">
                    <h3><?= $exp['company'] ?></h3>
                    <div class="details">
                        <span class="position"><?= $exp['position'] ?></span>
                    </div>
                    <div class="dates">
                        <?php if (!empty($exp['start_date'])): ?>
                            <span><?= date('M Y', strtotime($exp['start_date'])) ?></span> -
                        <?php endif; ?>
                        <?php if (!empty($exp['end_date'])): ?>
                            <span><?= date('M Y', strtotime($exp['end_date'])) ?></span>
                        <?php else: ?>
                            <span>Present</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($exp['description'])): ?>
                        <p class="description"><?= $exp['description'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
    
    <?php if (!empty($resume['skills'])): ?>
        <section class="skills">
            <h2>Skills</h2>
            <ul>
                <?php foreach ($resume['skills'] as $skill): ?>
                    <li>
                        <span class="skill-name"><?= $skill['skill'] ?></span>
                        <?php if (!empty($skill['level'])): ?>
                            <span class="skill-level">(<?= $skill['level'] ?>)</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
    
    <?php if (!empty($resume['projects'])): ?>
        <section class="projects">
            <h2>Projects</h2>
            <?php foreach ($resume['projects'] as $project): ?>
                <div class="project-item">
                    <h3><?= $project['name'] ?></h3>
                    <?php if (!empty($project['technologies'])): ?>
                        <div class="technologies"><?= $project['technologies'] ?></div>
                    <?php endif; ?>
                    <?php if (!empty($project['description'])): ?>
                        <p class="description"><?= $project['description'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</div>