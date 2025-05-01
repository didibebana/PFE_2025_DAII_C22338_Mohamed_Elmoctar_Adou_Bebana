import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput";
import TextInput from "@/Components/TextInput";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head, Link, useForm} from "@inertiajs/react";

export default function Create({auth,roles}) {

    const {data, setData, post, errors, processing} = useForm({
        nom: '',
        email: '',
        password: '',
        password_confirmation: '',
        role_id: '',
    })

    const onSubmit = (e) => {
        e.preventDefault();
        post(route('user.store'), {
            onError: (errors) => {
                console.log("Erreurs :", errors);
            },
            onSuccess: () => {
                console.log("✅ Utilisateur ajouté !");
            }
        });
    }

    return(

    <AuthenticatedLayout
    user={auth.user}
    header={
        <div className="flex justify-between items-center">
            <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
               Ajouter un Utilisateur
            </h2>
        </div>
    }
    >
        <Head title="Utilisateurs" />

        <div className="py-12">
            <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div className="p-6 text-gray-900 dark:text-gray-100">
                        <form onSubmit={onSubmit} className="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <div>
                                <InputLabel htmlFor="nom" value="Nom" />
                                <TextInput
                                    id="nom"
                                    type="text"
                                    name="nom"
                                    value={data.nom}
                                    className="mt-1 block w-full"
                                    autoComplete="nom"
                                    isFocused={true}
                                    onChange={(e) => setData('nom', e.target.value)}
                                />
                                <InputError message={errors.nom} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="email" value="Email" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    className="mt-1 block w-full"
                                    autoComplete="email"
                                    onChange={(e) => setData('email', e.target.value)}
                                />
                                <InputError message={errors.email} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="password" value="Mot de passe" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    name="password"
                                    value={data.password}
                                    className="mt-1 block w-full"
                                    autoComplete="new-password"
                                    onChange={(e) => setData('password', e.target.value)}
                                />
                                <InputError message={errors.password} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="password_confirmation" value="Confirmer le mot de passe" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    value={data.password_confirmation}
                                    className="mt-1 block w-full"
                                    autoComplete="new-password_confirmation"
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                />
                                <InputError message={errors.password_confirmation} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="role_id" value="Role" />
                                <SelectInput
                                    id="role_id"
                                    name="role_id"
                                    value={data.role_id}
                                    className="mt-1 block w-full"
                                    onChange={(e) => setData('role_id', e.target.value)}
                                >
                                    <option value="">-- Choisir un role --</option>
                                    {roles && roles.length > 0 && roles.map((role)=> (
                                        <option key={role.id} value={role.id}>
                                            {role.nom}
                                        </option>
                                    ))}
                                </SelectInput>
                                <InputError message={errors.role_id} className="mt-2" />
                            </div>
                            <div className="mt-4 text-right">
                                <Link href={route('user.index')}>
                                    <button className="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                        Retour
                                    </button>
                                </Link>
                                <button type="submit" className="px-4 py-2 ml-4 bg-indigo-600 dark:bg-indigo-400 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-500" disabled={processing}>
                                    Enregistrer
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
    )
}

