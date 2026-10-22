
const menuToggle = document.getElementById("menuToggle");
const navLinks = document.getElementById("navLinks");
const themeToggle = document.getElementById("themeToggle");

// Menu trên điện thoại
if (menuToggle && navLinks) {
    menuToggle.addEventListener("click", function () {
        const isOpen = navLinks.classList.toggle("active");

        menuToggle.textContent = isOpen ? "✕" : "☰";
        menuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    navLinks.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            navLinks.classList.remove("active");
            menuToggle.textContent = "☰";
            menuToggle.setAttribute("aria-expanded", "false");
        });
    });
}

// Chuyển chế độ sáng/tối
if (themeToggle) {
    themeToggle.addEventListener("click", function () {
        const darkMode = document.body.classList.toggle("dark-mode");

        themeToggle.textContent = darkMode ? "☀️" : "🌙";
        console.log("Chế độ hiện tại:", darkMode ? "Tối" : "Sáng");
    });
}

/* Tai danh sach du an tu MySQL */
async function loadPublicProjects() {
    const container = document.getElementById('projectsContainer');

    if (!container) return;

    try {
        const response = await fetch('/public-projects.php');
        const result = await response.json();

        if (!response.ok || result.status !== 'ok') {
            throw new Error('Khong the tai du an');
        }

        container.replaceChildren();

        if (!result.data || result.data.length === 0) {
            const emptyMessage = document.createElement('p');
            emptyMessage.textContent = 'Chua co du an nao.';
            container.appendChild(emptyMessage);
            return;
        }

        result.data.forEach((project, index) => {
            const card = document.createElement('article');
            card.className = 'project-card';

            const number = document.createElement('div');
            number.className = 'project-number';
            number.textContent = String(index + 1).padStart(2, '0');

            const title = document.createElement('h3');
            title.textContent = project.title || 'Du an';

            const description = document.createElement('p');
            description.textContent = project.description || '';

            const tags = document.createElement('div');
            tags.className = 'project-tags';

            (project.technologies || '')
                .split(',')
                .map(item => item.trim())
                .filter(Boolean)
                .forEach(technology => {
                    const tag = document.createElement('span');
                    tag.textContent = technology;
                    tags.appendChild(tag);
                });

            card.append(number, title, description, tags);

            if (project.project_url) {
                try {
                    const url = new URL(project.project_url);

                    if (url.protocol === 'https:' || url.protocol === 'http:') {
                        const link = document.createElement('a');
                        link.className = 'project-link';
                        link.href = url.href;
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        link.textContent = 'Xem dự án →';
                        card.appendChild(link);
                    }
                } catch {
                    // Bo qua lien ket khong hop le
                }
            }

            container.appendChild(card);
        });
    } catch (error) {
        console.error('Loi tai du an:', error);

        container.replaceChildren();

        const message = document.createElement('p');
        message.textContent =
            'Không thể tải danh sách dự án. Vui lòng thử lại sau.';
        container.appendChild(message);
    }
}

loadPublicProjects();
