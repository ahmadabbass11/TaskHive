import { Link } from "@inertiajs/react";

export default function GuestLayout({ children }) {
    return (
        <div className="flex min-h-screen flex-col items-center bg-gradient-to-r from-purple-700 to-purple-900 pt-6 sm:justify-center sm:pt-0">
            {/* Brand Name or Custom Logo */}
            <div>
                <Link href="/" className="text-white text-3xl font-bold">
                    Task Hive
                </Link>
            </div>

            {/* Auth Container */}
            <div className="mt-6 w-full bg-white px-6 py-6 shadow-lg sm:max-w-md sm:rounded-lg">
                {children}
            </div>
        </div>
    );
}

