import {
    ProfileVideoSection,
    ProjectMedia,
    TextProse,
    TypingText,
} from '../components';
import {
    formatIndex,
    limitText,
    uniqueValues,
} from '../utils';

export function HomePage({ home, profile, skills, projects, services, blogs, socialLinks, app }) {
    const site = app?.site ?? {};
    const technologies = profile?.technology_list ?? [];
    const heroTechnologies = technologies.slice(0, 5);
    const focusItems = uniqueValues([
        profile?.job_title,
        'Laravel Development',
        'Frontend Interfaces',
        'MySQL Database Design',
    ]);
    const locationItems = uniqueValues([
        profile?.address ? limitText(profile.address, 48) : '',
        'Por Senchey, Phnom Penh',
        'Phnom Penh, Cambodia',
    ]);

    return (
        <>
            <section className="hero-section">
                <div className="container">
                    <div className="hero-grid">
                        <div className="hero-card">
                            <p className="section-eyebrow">{home?.subtitle ?? profile?.job_title ?? 'Laravel Developer'}</p>
                            <h1 className="hero-title">
                                <span className="hero-name-text">{profile?.full_name ?? 'Your Name'}</span>
                            </h1>
                            <p className="hero-role">{profile?.job_title ?? 'Full Stack Developer'}</p>
                            <p className="hero-summary">{home?.short_description ?? profile?.short_intro ?? 'I build modern websites and web applications using Laravel.'}</p>

                            <div className="hero-actions">
                                <a href={home?.hire_me_link ?? app?.urls?.contact} className="site-btn site-btn-primary">Hire Me</a>

                                {profile?.cv_url && (
                                    <a
                                        href={profile.cv_url}
                                        className="site-btn site-btn-secondary"
                                        download={profile.cv_download_name ?? 'cv.pdf'}
                                    >
                                        {home?.cv_button_text ?? 'Download CV'}
                                    </a>
                                )}
                            </div>

                            {socialLinks?.length > 0 && (
                                <div className="chip-row mt-4">
                                    {socialLinks.map((socialLink) => (
                                        <a key={socialLink.platform} href={socialLink.url} className="site-chip site-chip-link" target="_blank" rel="noopener noreferrer">
                                            {socialLink.platform}
                                        </a>
                                    ))}
                                </div>
                            )}

                            {heroTechnologies.length > 0 && (
                                <div className="hero-stack">
                                    <span>Core stack</span>
                                    <div className="chip-row compact">
                                        {heroTechnologies.map((technology) => (
                                            <span key={technology} className="site-chip">{technology}</span>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        <div className="hero-visual-card">
                            <div className="hero-visual-frame">
                                {profile?.profile_image_url ? (
                                    <img src={profile.profile_image_url} className="hero-portrait" alt="Profile" />
                                ) : (
                                    <div className="image-placeholder hero-placeholder">Profile Image</div>
                                )}

                                <div className="floating-note floating-note-top">
                                    <span>Focused on</span>
                                    <TypingText
                                        className="typing-role"
                                        words={focusItems}
                                        typingSpeed={85}
                                        pauseDuration={1500}
                                        fallback={focusItems[0] ?? profile?.job_title ?? 'Laravel Development'}
                                    />
                                </div>

                                <div className="floating-note floating-note-bottom">
                                    <span>Based in</span>
                                    <TypingText
                                        className="typing-role typing-address"
                                        words={locationItems}
                                        typingSpeed={70}
                                        pauseDuration={1700}
                                        fallback={locationItems[0] ?? limitText(profile?.address ?? 'Phnom Penh, Cambodia', 48)}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <ProfileVideoSection
                profile={profile}
                eyebrow="Profile Video"
                title="A video link connected to the portfolio profile"
                description="Use this section to open or play the video attached to the profile record."
            />

            <section className="section-block section-tight">
                <div className="container">
                    <div className="metric-grid">
                        <article className="metric-card">
                            <span className="metric-label">Experience</span>
                            <strong>{profile?.experience ?? 'Growing through hands-on Laravel projects.'}</strong>
                        </article>
                        <article className="metric-card">
                            <span className="metric-label">Education</span>
                            <strong>{limitText(profile?.education ?? 'Information Technology background.', 90) || 'Information Technology background.'}</strong>
                        </article>
                        <article className="metric-card">
                            <span className="metric-label">Location</span>
                            <strong>{profile?.address ?? 'Phnom Penh, Cambodia'}</strong>
                        </article>
                    </div>
                </div>
            </section>

            <section className="section-block">
                <div className="container">
                    <div className="section-heading">
                        <div>
                            <p className="section-eyebrow">Capability</p>
                            <h2>Skills that support the work</h2>
                        </div>
                        <p>Practical strengths built around Laravel, database work, clean interfaces, and maintainable web application delivery.</p>
                    </div>

                    <div className="skill-grid">
                        {skills?.length > 0 ? skills.map((skill) => (
                            <article key={skill.skill_name} className="skill-card">
                                <div className="skill-card-row">
                                    <strong>{skill.skill_name}</strong>
                                    <span>{skill.percentage}%{skill.level ? ` | ${skill.level}` : ''}</span>
                                </div>
                                <div className="site-meter">
                                    <span style={{ width: `${skill.percentage}%` }} />
                                </div>
                            </article>
                        )) : <div className="empty-state">Add skills in the admin area to show your technical strengths here.</div>}
                    </div>
                </div>
            </section>

            <section className="section-block">
                <div className="container">
                    <div className="section-heading">
                        <div>
                            <p className="section-eyebrow">Featured Work</p>
                            <h2>Selected projects from the portfolio</h2>
                        </div>
                        <a href={app?.urls?.projects} className="site-btn site-btn-secondary">View All Projects</a>
                    </div>

                    <div className="project-grid">
                        {projects?.length > 0 ? projects.map((project) => (
                            <article key={project.slug} className="project-card">
                                <ProjectMedia project={project} mediaClass="card-media" placeholderText="Project Preview" />

                                <div className="card-content">
                                    <p className="card-kicker">{project.category?.name ?? 'Project'}</p>
                                    <h3>{project.title}</h3>
                                    <p>{limitText(project.description, 120)}</p>
                                    <div className="card-meta">{project.technology_used}</div>
                                    <a href={project.detail_url} className="site-inline-link">View Details</a>
                                </div>
                            </article>
                        )) : <div className="empty-state">Featured projects will appear here after you add them.</div>}
                    </div>
                </div>
            </section>

            <section className="section-block">
                <div className="container">
                    <div className="section-heading">
                        <div>
                            <p className="section-eyebrow">Services</p>
                            <h2>How I can contribute</h2>
                        </div>
                        <a href={app?.urls?.services} className="site-btn site-btn-secondary">View All Services</a>
                    </div>

                    <div className="service-grid">
                        {services?.length > 0 ? services.map((service, index) => (
                            <article key={`${service.title}-${index}`} className="service-card">
                                <span className="service-index">{formatIndex(index)}</span>
                                <h3>{service.title}</h3>
                                <p>{service.short_description}</p>
                            </article>
                        )) : <div className="empty-state">Service information has not been added yet.</div>}
                    </div>
                </div>
            </section>

            <section className="section-block">
                <div className="container">
                    <div className="section-heading">
                        <div>
                            <p className="section-eyebrow">Latest Writing</p>
                            <h2>Recent articles and updates</h2>
                        </div>
                        <a href={app?.urls?.blog} className="site-btn site-btn-secondary">Visit Blog</a>
                    </div>

                    <div className="blog-grid">
                        {blogs?.length > 0 ? blogs.map((blog) => (
                            <article key={blog.slug} className="blog-card">
                                <div className="card-content">
                                    <p className="card-kicker">{blog.category?.name ?? 'Blog'}</p>
                                    <h3>{blog.title}</h3>
                                    <p>{blog.short_description}</p>
                                    <div className="card-meta">{blog.published_label}</div>
                                    <a href={blog.detail_url} className="site-inline-link">Read Article</a>
                                </div>
                            </article>
                        )) : <div className="empty-state">Blog posts will appear here after they are published.</div>}
                    </div>
                </div>
            </section>

            <section className="section-block">
                <div className="container">
                    <div className="story-grid">
                        <article className="story-card">
                            <p className="section-eyebrow">About This Portfolio</p>
                            <h2>A clear presentation of skills, work, and professional goals.</h2>
                            <p className="site-prose">{profile?.about_me ?? 'Add your background story in the profile section.'}</p>

                            {technologies.length > 0 && (
                                <div className="chip-row mt-4">
                                    {technologies.map((technology) => (
                                        <span key={technology} className="site-chip">{technology}</span>
                                    ))}
                                </div>
                            )}
                        </article>

                        <article className="contact-card">
                            <p className="section-eyebrow">Contact</p>
                            <h2>Available for internships and project discussions.</h2>
                            <ul className="detail-list">
                                <li><strong>Email</strong><span>{profile?.email ?? site.contact_email ?? '-'}</span></li>
                                <li><strong>Phone</strong><span>{profile?.phone ?? site.contact_phone ?? '-'}</span></li>
                                <li><strong>Address</strong><span>{profile?.address ?? site.address ?? '-'}</span></li>
                            </ul>
                            <a href={app?.urls?.contact} className="site-btn site-btn-primary mt-4">Send a Message</a>
                        </article>
                    </div>
                </div>
            </section>
        </>
    );
}

export function AboutPage({ profile, skills, app }) {
    const site = app?.site ?? {};
    const technologies = profile?.technology_list ?? [];

    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card">
                        <p className="section-eyebrow">About</p>
                        <h1>About Me</h1>
                        <p>Background, learning path, personal goals, and the technical focus behind this portfolio.</p>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    <div className="about-grid">
                        <div className="media-card">
                            {profile?.profile_image_url ? (
                                <img src={profile.profile_image_url} className="card-media large-media" alt="Profile" />
                            ) : (
                                <div className="card-media card-media-placeholder large-media">Profile Image</div>
                            )}
                        </div>

                        <article className="story-card">
                            <p className="section-eyebrow">{profile?.job_title ?? 'Full Stack Developer'}</p>
                            <h2>{profile?.full_name ?? 'Your Name'}</h2>
                            <p className="hero-summary">{profile?.short_intro ?? 'Write a short introduction here.'}</p>
                            <TextProse text={profile?.about_me} fallback="Write your story here." />
                        </article>
                    </div>
                </div>
            </section>

            <ProfileVideoSection
                profile={profile}
                eyebrow="Video"
                title="Profile video and external media link"
                description="If a direct embed is available it will play here, otherwise the section provides a quick link to open the video."
            />

            <section className="section-block section-tight">
                <div className="container">
                    <div className="metric-grid">
                        <article className="metric-card">
                            <span className="metric-label">Experience</span>
                            <strong>{profile?.experience ?? '-'}</strong>
                        </article>
                        <article className="metric-card">
                            <span className="metric-label">Education</span>
                            <strong>{limitText(profile?.education ?? '-', 110) || '-'}</strong>
                        </article>
                        <article className="metric-card">
                            <span className="metric-label">Goals</span>
                            <strong>{profile?.goals ?? '-'}</strong>
                        </article>
                    </div>
                </div>
            </section>

            <section className="section-block">
                <div className="container">
                    <div className="story-grid">
                        <article className="contact-card">
                            <p className="section-eyebrow">Contact Details</p>
                            <h2>Direct information</h2>
                            <ul className="detail-list">
                                <li><strong>Email</strong><span>{profile?.email ?? site.contact_email ?? '-'}</span></li>
                                <li><strong>Phone</strong><span>{profile?.phone ?? site.contact_phone ?? '-'}</span></li>
                                <li><strong>Address</strong><span>{profile?.address ?? site.address ?? '-'}</span></li>
                            </ul>
                        </article>

                        <article className="story-card">
                            <p className="section-eyebrow">Technologies</p>
                            <h2>Tools I work with</h2>
                            {technologies.length > 0 ? (
                                <div className="chip-row">
                                    {technologies.map((technology) => (
                                        <span key={technology} className="site-chip">{technology}</span>
                                    ))}
                                </div>
                            ) : <div className="empty-state">Add technology keywords to your profile to show them here.</div>}
                        </article>
                    </div>
                </div>
            </section>

            <section className="section-block">
                <div className="container">
                    <div className="section-heading">
                        <div>
                            <p className="section-eyebrow">Skill Overview</p>
                            <h2>Technical strengths</h2>
                        </div>
                        <p>These are the main technical areas that support the projects shown in the portfolio.</p>
                    </div>

                    <div className="skill-grid">
                        {skills?.length > 0 ? skills.map((skill) => (
                            <article key={skill.skill_name} className="skill-card">
                                <div className="skill-card-row">
                                    <strong>{skill.skill_name}</strong>
                                    <span>{skill.percentage}%</span>
                                </div>
                                <div className="site-meter">
                                    <span style={{ width: `${skill.percentage}%` }} />
                                </div>
                            </article>
                        )) : <div className="empty-state">Skills have not been added yet.</div>}
                    </div>
                </div>
            </section>
        </>
    );
}
