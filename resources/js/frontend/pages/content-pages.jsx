import {
    Pagination,
    ProjectMedia,
    TextProse,
} from '../components';
import {
    formatIndex,
    limitText,
    splitCsv,
} from '../utils';

export function ProjectsPage({ projects, categories }) {
    const items = projects?.items ?? [];

    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card">
                        <p className="section-eyebrow">Projects</p>
                        <h1>Work built with Laravel and practical web technologies.</h1>
                        <p>A selection of portfolio pieces focused on responsive layouts, structured backend logic, and database-driven content.</p>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    {categories?.length > 0 && (
                        <div className="chip-row mb-4">
                            {categories.map((category) => (
                                <span key={category.name} className="site-chip">{category.name}</span>
                            ))}
                        </div>
                    )}

                    <div className="project-grid">
                        {items.length > 0 ? items.map((project) => (
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
                        )) : <div className="empty-state">No projects have been published yet.</div>}
                    </div>

                    <div className="pagination-wrap mt-5">
                        <Pagination pagination={projects?.pagination} />
                    </div>
                </div>
            </section>
        </>
    );
}

export function ProjectDetailPage({ project, app }) {
    const technologies = splitCsv(project?.technology_used);

    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card detail-hero-card">
                        <p className="section-eyebrow">{project?.category?.name ?? 'Project'}</p>
                        <h1>{project?.title}</h1>
                        <p>{limitText(project?.description, 180)}</p>

                        <div className="hero-actions mt-4">
                            {project?.project_url && (
                                <a href={project.project_url} target="_blank" rel="noopener noreferrer" className="site-btn site-btn-primary">Live Project</a>
                            )}

                            {project?.github_url && (
                                <a href={project.github_url} target="_blank" rel="noopener noreferrer" className="site-btn site-btn-secondary">GitHub</a>
                            )}

                            <a href={app?.urls?.projects} className="site-btn site-btn-secondary">Back to Projects</a>
                        </div>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    {project?.demo_video && (
                        <div className="media-card mb-4">
                            <ProjectMedia project={project} mediaClass="detail-media" placeholderText="Project Preview" />
                        </div>
                    )}

                    <div className="detail-grid">
                        <article className="story-card">
                            <p className="section-eyebrow">Overview</p>
                            <h2>Project description</h2>
                            <TextProse text={project?.description} />
                        </article>

                        <aside className="contact-card">
                            <p className="section-eyebrow">Technology Stack</p>
                            <h2>What was used</h2>

                            {technologies.length > 0 ? (
                                <div className="chip-row">
                                    {technologies.map((technology) => (
                                        <span key={technology} className="site-chip">{technology}</span>
                                    ))}
                                </div>
                            ) : <p className="site-prose mb-0">{project?.technology_used || '-'}</p>}
                        </aside>
                    </div>
                </div>
            </section>
        </>
    );
}

export function ServicesPage({ services, app }) {
    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card">
                        <p className="section-eyebrow">Services</p>
                        <h1>Practical frontend and Laravel development support.</h1>
                        <p>Services focused on building, refining, and maintaining portfolio websites, admin dashboards, and data-driven web applications.</p>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    <div className="service-grid">
                        {services?.length > 0 ? services.map((service, index) => (
                            <article key={`${service.title}-${index}`} className="service-card">
                                <span className="service-index">{formatIndex(index)}</span>
                                <h3>{service.title}</h3>
                                <p>{service.short_description}</p>
                            </article>
                        )) : <div className="empty-state">No services are available yet.</div>}
                    </div>

                    <div className="cta-banner mt-5">
                        <div>
                            <p className="section-eyebrow mb-2">Need a project?</p>
                            <h2>Let's talk about your Laravel website or dashboard.</h2>
                            <p className="mb-0">Use the contact form to discuss features, improvements, or portfolio work that needs a cleaner frontend presentation.</p>
                        </div>
                        <a href={app?.urls?.contact} className="site-btn site-btn-primary">Contact Me</a>
                    </div>
                </div>
            </section>
        </>
    );
}

export function BlogPage({ blogs }) {
    const items = blogs?.items ?? [];

    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card">
                        <p className="section-eyebrow">Blog</p>
                        <h1>Thoughts on Laravel, learning, and project building.</h1>
                        <p>Short writing collected around web development practice, technical growth, and lessons from database-driven applications.</p>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    <div className="blog-grid">
                        {items.length > 0 ? items.map((blog) => (
                            <article key={blog.slug} className="blog-card">
                                {blog.image_url && (
                                    <img src={blog.image_url} className="card-media" alt={blog.title} />
                                )}

                                <div className="card-content">
                                    <p className="card-kicker">{blog.category?.name ?? 'Blog'}</p>
                                    <h3>{blog.title}</h3>
                                    <p>{blog.short_description}</p>
                                    <div className="card-meta">{blog.published_label}</div>
                                    <a href={blog.detail_url} className="site-inline-link">Read More</a>
                                </div>
                            </article>
                        )) : <div className="empty-state">No blog posts have been published yet.</div>}
                    </div>

                    <div className="pagination-wrap mt-5">
                        <Pagination pagination={blogs?.pagination} />
                    </div>
                </div>
            </section>
        </>
    );
}

export function BlogDetailPage({ blog, app }) {
    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card detail-hero-card">
                        <p className="section-eyebrow">{blog?.category?.name ?? 'Blog'}</p>
                        <h1>{blog?.title}</h1>
                        <p>{blog?.short_description ?? 'Article details'}</p>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    {blog?.image_url && (
                        <div className="media-card mb-4">
                            <img src={blog.image_url} className="card-media detail-media" alt={blog.title} />
                        </div>
                    )}

                    <div className="detail-grid">
                        <article className="story-card">
                            <p className="section-eyebrow">Article</p>
                            <h2>Content</h2>
                            <TextProse text={blog?.content} />
                        </article>

                        <aside className="contact-card">
                            <p className="section-eyebrow">Details</p>
                            <h2>Post information</h2>
                            <ul className="detail-list">
                                <li><strong>Author</strong><span>{blog?.author || '-'}</span></li>
                                <li><strong>Date</strong><span>{blog?.published_label ?? 'Draft'}</span></li>
                                <li><strong>Category</strong><span>{blog?.category?.name ?? 'Blog'}</span></li>
                            </ul>
                            <a href={app?.urls?.blog} className="site-btn site-btn-secondary mt-4">Back to Blog</a>
                        </aside>
                    </div>
                </div>
            </section>
        </>
    );
}
