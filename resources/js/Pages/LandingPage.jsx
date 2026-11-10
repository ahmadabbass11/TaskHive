import { Link } from '@inertiajs/react';
import { useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUsers, faTasks, faChartLine, faSignInAlt, faUserPlus } from '@fortawesome/free-solid-svg-icons';

export default function LandingPage() {
    useEffect(() => {
        // Ensure particles.js is loaded from CDN
        if (window.particlesJS) {
            window.particlesJS("particles-js", {
                particles: {
                    number: { value: 80 },
                    size: { value: 3 },
                    move: { speed: 2 },
                    line_linked: { enable: true, opacity: 0.5 },
                }
            });
        } else {
            console.error("particles.js failed to load from CDN");
        }
    }, []);

    return (
        <div className="relative min-h-screen flex flex-col items-center justify-center bg-gray-900 text-white">
            {/* Particle Background */}
            <div id="particles-js" className="absolute top-0 left-0 w-full h-full z-0"></div>

            {/* Content */}
            <div className="relative z-10 text-center">
                <h1 className="text-4xl md:text-5xl font-bold text-white drop-shadow-md">
                    Welcome to <span className="text-teal-400">TaskHive</span>
                </h1>
                <p className="mt-2 text-lg text-gray-300">
                    Collaborate, manage projects, and stay productive with ease.
                </p>

                {/* Buttons */}
                <div className="mt-6 flex justify-center gap-4">
                    <Link href="/login" className="bg-teal-500 px-6 py-3 rounded-md text-white font-semibold shadow-md hover:bg-teal-600 transition transform hover:scale-110">
                        <FontAwesomeIcon icon={faSignInAlt} className="mr-2" /> Login
                    </Link>
                    <Link href="/register" className="border border-white px-6 py-3 rounded-md text-white font-semibold shadow-md hover:bg-white hover:text-black transition transform hover:scale-110">
                        <FontAwesomeIcon icon={faUserPlus} className="mr-2" /> Sign Up
                    </Link>
                </div>
            </div>

            {/* Features Section */}
            <div className="relative z-10 mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl">
                <FeatureCard
                    icon={faUsers}
                    title="Team Collaboration"
                    description="Work seamlessly with your team in real-time."
                />
                <FeatureCard
                    icon={faTasks}
                    title="Project Management"
                    description="Keep track of all your tasks and deadlines."
                />
                <FeatureCard
                    icon={faChartLine}
                    title="Analytics & Insights"
                    description="Monitor team progress and project success."
                />
            </div>
        </div>
    );
}

// Feature Card Component
function FeatureCard({ icon, title, description }) {
    return (
        <div className="bg-gray-800 p-6 rounded-lg text-center shadow-lg hover:scale-105 transition-transform">
            <FontAwesomeIcon icon={icon} className="text-4xl text-teal-400" />
            <h3 className="mt-4 text-xl font-semibold">{title}</h3>
            <p className="text-gray-400 mt-2">{description}</p>
        </div>
    );
}
