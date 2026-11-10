import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => {
                window.location.href = route("main.homepage");
                reset('password');
            },
        });
    };

    return (
        <GuestLayout>
            <Head title="Login" />

            <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-purple-600 via-pink-500 to-red-500">
                <div className="w-full max-w-lg p-10 rounded-3xl shadow-2xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-md transform transition hover:scale-105">
                    <h2 className="text-center text-4xl font-bold text-gray-900 dark:text-white">Welcome Back</h2>
                    {status && <div className="mt-2 text-center text-sm text-green-600">{status}</div>}

                    <form onSubmit={submit} className="space-y-6 mt-6">
                        <div className="relative">
                            <span className="absolute left-3 top-3 text-gray-500"><i className="fas fa-envelope"></i></span>
                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                placeholder="Email Address"
                                className="pl-10 w-full rounded-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 focus:ring-2 focus:ring-purple-400"
                                autoComplete="username"
                                isFocused={true}
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
                                className="pl-10 w-full rounded-full bg-white dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-300 focus:ring-2 focus:ring-purple-400"
                                autoComplete="current-password"
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div className="flex items-center justify-between">
                            <label className="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                <Checkbox
                                    name="remember"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="mr-2 h-4 w-4 text-purple-600"
                                />
                                Remember me
                            </label>
                            {canResetPassword && (
                                <Link href={route('password.request')} className="text-sm text-purple-600 hover:underline">
                                    Forgot password?
                                </Link>
                            )}
                        </div>

                        <PrimaryButton className="w-full rounded-full bg-purple-600 py-3 text-white font-semibold hover:bg-purple-700 transition transform hover:scale-105" disabled={processing}>
                            Log in
                        </PrimaryButton>
                    </form>

                    <p className="mt-6 text-center text-sm text-gray-700 dark:text-gray-300">
                        Don't have an account?{' '}
                        <Link href={route('register')} className="text-purple-600 hover:underline">
                            Sign up
                        </Link>
                    </p>
                </div>
            </div>
        </GuestLayout>
    );
}
