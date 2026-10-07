import { Form, Head, Link } from '@inertiajs/react';
import InputError from '@/components/input-error';
import PasskeyVerify from '@/components/passkey-verify';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

type Props = {
    status?: string;
    canResetPassword: boolean;
};

const champ =
    'w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm';

export default function Login({ status, canResetPassword }: Props) {
    return (
        <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700 p-4 relative overflow-hidden">
            <Head title="Connexion" />
            <div className="blob bg-gold-400 w-96 h-96 top-0 right-0" />
            <div className="blob bg-brand-400 w-80 h-80 bottom-0 left-0" style={{ animationDelay: '2s' }} />

            <div className="relative w-full max-w-md">
                <div className="text-center mb-8">
                    <Link href="/" className="inline-flex w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center mb-4">
                        <span className="text-white font-display font-bold text-2xl">KF</span>
                    </Link>
                    <h1 className="font-display font-bold text-white text-2xl">Connexion</h1>
                    <p className="text-slate-300 text-sm mt-1">Accédez à votre espace personnel</p>
                </div>

                <div className="bg-white rounded-2xl p-6 shadow-2xl">
                    {status && <div className="mb-4 text-center text-sm font-medium text-emerald-600">{status}</div>}

                    <Form {...store.form()} resetOnSuccess={['password']} className="space-y-4">
                        {({ processing, errors }) => (
                            <>
                                <div>
                                    <label htmlFor="email" className="text-xs font-medium text-slate-600 mb-1.5 block">Adresse email</label>
                                    <input id="email" type="email" name="email" required autoFocus autoComplete="email" placeholder="vous@exemple.com" className={champ} />
                                    <InputError message={errors.email} />
                                </div>
                                <div>
                                    <label htmlFor="password" className="text-xs font-medium text-slate-600 mb-1.5 block">Mot de passe</label>
                                    <input id="password" type="password" name="password" required autoComplete="current-password" placeholder="••••••••" className={champ} />
                                    <InputError message={errors.password} />
                                </div>
                                <div className="flex items-center justify-between text-sm">
                                    <label className="flex items-center gap-2 text-slate-600">
                                        <input type="checkbox" name="remember" className="rounded border-slate-300" /> Se souvenir de moi
                                    </label>
                                    {canResetPassword && (
                                        <Link href={request()} className="text-brand-600 font-medium hover:text-brand-700">
                                            Mot de passe oublié ?
                                        </Link>
                                    )}
                                </div>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    data-test="login-button"
                                    className="w-full py-3 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white font-semibold shadow-md hover:shadow-lg transition-all disabled:opacity-60"
                                >
                                    {processing ? 'Connexion…' : 'Se connecter'}
                                </button>
                            </>
                        )}
                    </Form>

                    <div className="mt-4">
                        <PasskeyVerify />
                    </div>

                    <p className="text-center text-sm text-slate-500 mt-4">
                        Pas de compte ?{' '}
                        <Link href={register()} className="text-brand-600 font-medium hover:text-brand-700">
                            Créer un compte
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}
