import { Form, Head, Link } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { login } from '@/routes';
import { store } from '@/routes/register';

const champ =
    'w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm';
const libelle = 'text-xs font-medium text-slate-600 mb-1.5 block';

export default function Register() {
    return (
        <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700 p-4 relative overflow-hidden">
            <Head title="Créer un compte" />
            <div className="blob bg-gold-400 w-96 h-96 top-0 right-0" />
            <div className="blob bg-brand-400 w-80 h-80 bottom-0 left-0" style={{ animationDelay: '2s' }} />

            <div className="relative w-full max-w-md">
                <div className="text-center mb-6">
                    <Link href="/" className="inline-flex w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center mb-4">
                        <span className="text-white font-display font-bold text-2xl">KF</span>
                    </Link>
                    <h1 className="font-display font-bold text-white text-2xl">Créer un compte</h1>
                    <p className="text-slate-300 text-sm mt-1">Investissez dès 100 000 FCFA</p>
                </div>

                <div className="bg-white rounded-2xl p-6 shadow-2xl">
                    <Form {...store.form()} resetOnSuccess={['password', 'password_confirmation']} disableWhileProcessing className="space-y-4">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid grid-cols-2 gap-3">
                                    <div>
                                        <label htmlFor="prenom" className={libelle}>Prénom</label>
                                        <input id="prenom" type="text" name="prenom" required autoFocus autoComplete="given-name" placeholder="Prénom" className={champ} />
                                        <InputError message={errors.prenom} />
                                    </div>
                                    <div>
                                        <label htmlFor="nom" className={libelle}>Nom</label>
                                        <input id="nom" type="text" name="nom" required autoComplete="family-name" placeholder="Nom" className={champ} />
                                        <InputError message={errors.nom} />
                                    </div>
                                </div>
                                <div>
                                    <label htmlFor="email" className={libelle}>Adresse email</label>
                                    <input id="email" type="email" name="email" required autoComplete="email" placeholder="vous@exemple.com" className={champ} />
                                    <InputError message={errors.email} />
                                </div>
                                <div>
                                    <label htmlFor="telephone" className={libelle}>Téléphone</label>
                                    <input id="telephone" type="tel" name="telephone" autoComplete="tel" placeholder="+221 ..." className={champ} />
                                    <InputError message={errors.telephone} />
                                </div>
                                <div>
                                    <label htmlFor="password" className={libelle}>Mot de passe</label>
                                    <input id="password" type="password" name="password" required autoComplete="new-password" placeholder="••••••••" className={champ} />
                                    <InputError message={errors.password} />
                                </div>
                                <div>
                                    <label htmlFor="password_confirmation" className={libelle}>Confirmer le mot de passe</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autoComplete="new-password" placeholder="••••••••" className={champ} />
                                    <InputError message={errors.password_confirmation} />
                                </div>
                                <label className="flex items-start gap-2 text-xs text-slate-600">
                                    <input type="checkbox" required className="mt-0.5 rounded border-slate-300" />
                                    J'accepte les conditions d'investissement et la politique de confidentialité.
                                </label>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    data-test="register-user-button"
                                    className="w-full py-3 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white font-semibold shadow-md hover:shadow-lg transition-all disabled:opacity-60"
                                >
                                    {processing ? 'Création…' : 'Créer mon compte'}
                                </button>
                            </>
                        )}
                    </Form>

                    <p className="text-center text-sm text-slate-500 mt-4">
                        Déjà inscrit ?{' '}
                        <Link href={login()} className="text-brand-600 font-medium hover:text-brand-700">
                            Se connecter
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}
