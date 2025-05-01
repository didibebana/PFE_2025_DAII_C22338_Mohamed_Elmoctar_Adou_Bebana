import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput";
import TextAreaInput from "@/Components/TextAreaInput";
import TextInput from "@/Components/TextInput";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head, Link, useForm} from "@inertiajs/react";

export default function Create({auth,axes}) {

    const {data, setData, post, errors, processing} = useForm({
        nom: '',
        description: '',
        axe_id: ''
    })

    const onSubmit = (e) => {
        e.preventDefault();
        post(route('sousaxe.store', data));
    }

    return(

    <AuthenticatedLayout
    user={auth.user}
    header={
        <div className="flex justify-between items-center">
            <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
               Ajouter un Sous-Axe
            </h2>
        </div>
    }
    >
        <Head title="Sous-Axes" />

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
                                <InputLabel htmlFor="description" value="Description" />
                                <TextAreaInput
                                    id="description"
                                    type="text"
                                    name="description"
                                    value={data.description}
                                    className="mt-1 block w-full"
                                    autoComplete="description"
                                    onChange={(e) => setData('description', e.target.value)}
                                />
                                <InputError message={errors.description} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="axe_id" value="Axe" />
                                <SelectInput
                                    id="axe_id"
                                    name="axe_id"
                                    value={data.axe_id}
                                    className="mt-1 block w-full"
                                    onChange={(e) => setData('axe_id', e.target.value)}
                                >
                                    <option value="">Sélectionnez un axe</option>
                                    {axes.map((axe) => (
                                        <option key={axe.id} value={axe.id}>{axe.nom}</option>
                                    ))}
                                </SelectInput>
                                <InputError message={errors.axe_id} className="mt-2" />
                            </div>
                            <div className="mt-4 text-right">
                                <Link href={route('axe.index')}>
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

