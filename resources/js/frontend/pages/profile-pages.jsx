import { useState } from 'react';
import { TextProse } from '../components';
import { getCsrfToken, isExternalUrl } from '../utils';

export function ResumePage({ profile, app }) {
    const site = app?.site ?? {};
    const technologies = profile?.technology_list ?? [];

    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card">
                        <p className="section-eyebrow">Resume</p>
                        <h1>Professional summary, education, and core technical profile.</h1>
                        <p>A clean resume view for quickly reviewing experience, background, goals, and downloadable CV information.</p>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    <div className="detail-grid">
                        <article className="story-card">
                            <p className="section-eyebrow">{profile?.job_title ?? 'Laravel Developer'}</p>
                            <h2>{profile?.full_name ?? 'Your Name'}</h2>
                            <p className="hero-summary">{profile?.short_intro ?? 'Add your short introduction to the profile section.'}</p>

                            <div className="resume-stack">
                                <div>
                                    <h3>Professional Summary</h3>
                                    <TextProse text={profile?.about_me} />
                                </div>
                                <div>
                                    <h3>Experience</h3>
                                    <TextProse text={profile?.experience} />
                                </div>
                                <div>
                                    <h3>Education</h3>
                                    <TextProse text={profile?.education} />
                                </div>
                                <div>
                                    <h3>Career Goals</h3>
                                    <TextProse text={profile?.goals} />
                                </div>
                            </div>
                        </article>

                        <aside className="sidebar-stack">
                            <article className="contact-card">
                                <p className="section-eyebrow">Contact</p>
                                <h2>Details</h2>
                                <ul className="detail-list">
                                    <li><strong>Email</strong><span>{profile?.email ?? site.contact_email ?? '-'}</span></li>
                                    <li><strong>Phone</strong><span>{profile?.phone ?? site.contact_phone ?? '-'}</span></li>
                                    <li><strong>Address</strong><span>{profile?.address ?? site.address ?? '-'}</span></li>
                                </ul>
                            </article>

                            <article className="contact-card">
                                <p className="section-eyebrow">Technologies</p>
                                <h2>Stack</h2>
                                {technologies.length > 0 ? (
                                    <div className="chip-row">
                                        {technologies.map((technology) => (
                                            <span key={technology} className="site-chip">{technology}</span>
                                        ))}
                                    </div>
                                ) : <div className="empty-state">Technology keywords have not been added yet.</div>}
                            </article>

                            <article className="contact-card">
                                <p className="section-eyebrow">CV</p>
                                <h2>Download file</h2>
                                {profile?.cv_url ? (
                                    <a
                                        href={profile.cv_url}
                                        download={profile.cv_download_name ?? 'cv.pdf'}
                                        className="site-btn site-btn-primary w-100"
                                    >
                                        Download CV
                                    </a>
                                ) : <div className="empty-state">No CV uploaded yet.</div>}
                            </article>
                        </aside>
                    </div>
                </div>
            </section>
        </>
    );
}

export function ContactPage({ profile, socialLinks, app }) {
    const site = app?.site ?? {};
    const initialValues = app?.formState?.old ?? {};
    const [form, setForm] = useState({
        name: initialValues.name ?? '',
        email: initialValues.email ?? '',
        subject: initialValues.subject ?? '',
        message: initialValues.message ?? '',
    });
    const [errors, setErrors] = useState(app?.formState?.errors ?? {});
    const [successMessage, setSuccessMessage] = useState(app?.flash?.success ?? '');
    const [submitError, setSubmitError] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (isSubmitting) {
            return;
        }

        setIsSubmitting(true);
        setSubmitError('');
        setSuccessMessage('');
        setErrors({});

        try {
            const response = await fetch(app?.urls?.contactStore, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify(form),
            });

            const payload = await response.json().catch(() => ({}));

            if (response.status === 422) {
                setErrors(payload.errors ?? {});
                return;
            }

            if (!response.ok) {
                throw new Error('Request failed.');
            }

            setSuccessMessage(payload.message ?? 'Your message has been sent successfully.');
            setForm({
                name: '',
                email: '',
                subject: '',
                message: '',
            });
        } catch {
            setSubmitError('The message could not be sent right now. Please try again in a moment.');
        } finally {
            setIsSubmitting(false);
        }
    };

    const updateField = (field, value) => {
        setForm((current) => ({
            ...current,
            [field]: value,
        }));

        if (errors[field]) {
            setErrors((current) => ({
                ...current,
                [field]: undefined,
            }));
        }
    };

    return (
        <>
            <section className="page-hero">
                <div className="container">
                    <div className="page-hero-card">
                        <p className="section-eyebrow">Contact</p>
                        <h1>Start a conversation about work, collaboration, or internships.</h1>
                        <p>Use the form to send a direct message and review the main contact details shown alongside it.</p>
                    </div>
                </div>
            </section>

            <section className="section-block section-tight">
                <div className="container">
                    {successMessage && (
                        <div className="site-alert site-alert-success mb-4">{successMessage}</div>
                    )}

                    {submitError && (
                        <div className="site-alert site-alert-error mb-4">{submitError}</div>
                    )}

                    <div className="detail-grid">
                        <article className="contact-card">
                            <p className="section-eyebrow">Contact Details</p>
                            <h2>Reach out directly</h2>
                            <ul className="detail-list">
                                <li><strong>Email</strong><span>{profile?.email ?? site.contact_email ?? '-'}</span></li>
                                <li><strong>Phone</strong><span>{profile?.phone ?? site.contact_phone ?? '-'}</span></li>
                                <li><strong>Address</strong><span>{profile?.address ?? site.address ?? '-'}</span></li>
                            </ul>

                            {socialLinks?.length > 0 && (
                                <div className="mt-4">
                                    <p className="section-eyebrow mb-2">Quick Links</p>
                                    <div className="chip-row">
                                        {socialLinks.map((socialLink) => (
                                            <a key={socialLink.platform} href={socialLink.url} className="site-chip site-chip-link" target={isExternalUrl(socialLink.url) ? '_blank' : undefined} rel={isExternalUrl(socialLink.url) ? 'noopener noreferrer' : undefined}>
                                                {socialLink.platform}
                                            </a>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </article>

                        <form className="contact-form-card" onSubmit={handleSubmit}>
                            <div className="form-row">
                                <label className="site-label" htmlFor="contact-name">Name</label>
                                <input
                                    id="contact-name"
                                    type="text"
                                    name="name"
                                    className="site-input"
                                    value={form.name}
                                    onChange={(event) => updateField('name', event.target.value)}
                                />
                                {errors.name?.[0] && <div className="site-error">{errors.name[0]}</div>}
                            </div>

                            <div className="form-row">
                                <label className="site-label" htmlFor="contact-email">Email</label>
                                <input
                                    id="contact-email"
                                    type="email"
                                    name="email"
                                    className="site-input"
                                    value={form.email}
                                    onChange={(event) => updateField('email', event.target.value)}
                                />
                                {errors.email?.[0] && <div className="site-error">{errors.email[0]}</div>}
                            </div>

                            <div className="form-row">
                                <label className="site-label" htmlFor="contact-subject">Subject</label>
                                <input
                                    id="contact-subject"
                                    type="text"
                                    name="subject"
                                    className="site-input"
                                    value={form.subject}
                                    onChange={(event) => updateField('subject', event.target.value)}
                                />
                                {errors.subject?.[0] && <div className="site-error">{errors.subject[0]}</div>}
                            </div>

                            <div className="form-row">
                                <label className="site-label" htmlFor="contact-message">Message</label>
                                <textarea
                                    id="contact-message"
                                    name="message"
                                    rows="6"
                                    className="site-input site-textarea"
                                    value={form.message}
                                    onChange={(event) => updateField('message', event.target.value)}
                                />
                                {errors.message?.[0] && <div className="site-error">{errors.message[0]}</div>}
                            </div>

                            <button type="submit" className="site-btn site-btn-primary" disabled={isSubmitting}>
                                {isSubmitting ? 'Sending...' : 'Send Message'}
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </>
    );
}
