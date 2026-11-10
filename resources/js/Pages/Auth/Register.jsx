// Enhanced Register.jsx
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset(),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <div className="flex min-h-screen items-center justify-center bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-600">
                <div className="w-full max-w-lg p-10 rounded-3xl shadow-2xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-md transform transition hover:scale-105">
                    <h2 className="text-center text-4xl font-bold text-gray-800 dark:text-white">Create Your Free Account</h2>
                    <p className="text-center text-sm text-gray-600 dark:text-gray-300 mb-6">Join us and get started today</p>

                    <form onSubmit={submit} className="space-y-6">
                        <div className="relative">
                            <span className="absolute left-3 top-3 text-gray-500"><i className="fas fa-user"></i></span>
                            <TextInput
                                id="name"
                                type="text"
                                name="name"
                                value={data.name}
                                placeholder="Full Name"
                                className="pl-10 w-full rounded-xl bg-white/90 dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                                onChange={(e) => setData('name', e.target.value)}
                            />
                            <InputError message={errors.name} className="mt-2" />
                        </div>

                        <div className="relative">
                            <span className="absolute left-3 top-3 text-gray-500"><i className="fas fa-envelope"></i></span>
                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                placeholder="Email Address"
                                className="pl-10 w-full rounded-xl bg-white/90 dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                                onChange={(e) => setData('email', e.target.value)}
                            />
                            <InputError message={errors.email} className="mt-2" />
                        </div>

                        <div className="relative">
                            <span className="absolute left-3 top-3 text-gray-500"><i className="fas fa-lock"></i></span>
                            <TextInput
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                placeholder="Password"
                                className="pl-10 w-full rounded-xl bg-white/90 dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div className="relative">
                            <span className="absolute left-3 top-3 text-gray-500"><i className="fas fa-lock-open"></i></span>
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                value={data.password_confirmation}
                                placeholder="Confirm Password"
                                className="pl-10 w-full rounded-xl bg-white/90 dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                            />
                            <InputError message={errors.password_confirmation} className="mt-2" />
                        </div>

                        <PrimaryButton className="w-full rounded-xl bg-indigo-600 py-3 text-white font-semibold hover:bg-indigo-700 transition transform hover:scale-105" disabled={processing}>
                            Register
                        </PrimaryButton>
                    </form>

                    <p className="mt-6 text-center text-sm text-gray-700 dark:text-gray-300">
                        Already have an account?{' '}
                        <Link href={route('login')} className="text-indigo-600 hover:underline dark:text-indigo-400">
                            Log in
                        </Link>
                    </p>
                </div>
            </div>
        </GuestLayout>
    );
}