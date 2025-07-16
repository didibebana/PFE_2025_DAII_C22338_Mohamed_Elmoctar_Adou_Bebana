import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextAreaInput from "@/Components/TextAreaInput";
import TextInput from "@/Components/TextInput";
import SelectInput from "@/Components/SelectInput";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head, Link, useForm} from "@inertiajs/react";

export default function Create({auth, action, sousaxes, responsables, taux_execution}) {

    const {data, setData, post, errors, processing} = useForm({
        nom: action.nom ||'',
        description: action.description ||'',
        statut: action.statut ||'',
        date_debut: action.date_debut ||'',
        date_fin: action.date_fin ||'',
        sous_axe_id: action.sous_axe_id ||'',
        responsable_id: action.responsable_id ||'',
        taux: taux_execution?.taux ?? 0,
        _method: 'PUT'
    })

    const onSubmit = (e) => {
        e.preventDefault();
        post(route('action.update', action.id));
    }

    const isAdmin = auth.user.role.nom === 'administrateur';

    const isResponsable = auth.user.role.nom === 'responsable_action';


    return(

    <AuthenticatedLayout
    user={auth.user}
    header={
        <div className="flex justify-between items-center">
            <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
               Modifier l'Action: "{action.nom}"
            </h2>
        </div>
    }
    >
        <Head title="Actions" />

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
                                <InputLabel htmlFor="statut" value="Statut" />
                                <SelectInput
                                    id="statut"
                                    name="statut"
                                    value={data.statut}
                                    className="mt-1 block w-full"
                                    onChange={(e) => setData('statut', e.target.value)}
                                >
                                    <option value="--">Selectionner une statut</option>
                                    <option value="A venir">A venir</option>
                                    <option value="En attente">En attente</option>
                                    <option value="En cours">En cours</option>
                                    <option value="Terminée">Terminée</option>
                                    <option value="Annulée">Annulée</option>
                                    <option value="En retard">En retard</option>
                                </SelectInput>
                                <InputError message={errors.statut} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="date_debut" value="Date de début" />
                                <TextInput
                                    id="date_debut"
                                    type="date"
                                    name="date_debut"
                                    value={data.date_debut}
                                    className="mt-1 block w-full"
                                    autoComplete="date_debut"
                                    onChange={(e) => setData('date_debut', e.target.value)}
                                />
                                <InputError message={errors.date_debut} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="date_fin" value="Date de fin" />
                                <TextInput
                                    id="date_fin"
                                    type="date"
                                    name="date_fin"
                                    value={data.date_fin}
                                    className="mt-1 block w-full"
                                    autoComplete="date_fin"
                                    onChange={(e) => setData('date_fin', e.target.value)}
                                />
                                <InputError message={errors.date_fin} className="mt-2" />
                            </div>
                            {isAdmin &&(<>
                                <div>
                            <InputLabel htmlFor="sous_axe_id" value="Sous Axe lié" />
                            <SelectInput
                                id="sous_axe_id"
                                name="sous_axe_id"
                                value={data.sous_axe_id}
                                className="mt-1 block w-full"
                                onChange={(e) => setData('sous_axe_id', e.target.value)}
                            >
                                <option value="">-- Choisir un sous-axe --</option>
                                {sousaxes.map((sousaxe) => (
                                    <option key={sousaxe.id} value={sousaxe.id}>
                                            {sousaxe.nom}
                                    </option>
                                ))}
                                </SelectInput>
                                <InputError message={errors.sousaxe_id} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="responsable_id" value="Responsable de cette Action" />
                                <SelectInput
                                    id="responsable_id"
                                    name="responsable_id"
                                    value={data.responsable_id}
                                    className="mt-1 block w-full"
                                    onChange={(e) => setData('responsable_id', e.target.value)}
                                >
                                    <option value="">-- Choisir un responsable --</option>
                                    {responsables.map((responsable) => (
                                        <option key={responsable.id} value={responsable.id}>
                                            {responsable.nom}
                                        </option>
                                    ))}
                                </SelectInput>
                                <InputError message={errors.responsable_id} className="mt-2" />
                            </div>
                            </>)}

                            <div>
                                <label className="block font-medium text-sm text-gray-700">Taux d'exécution (%)</label>
                                <TextInput
                                    type="number"
                                    min="0"
                                    max="100"
                                    step="0.1"
                                    value={data.taux}
                                    onChange={(e) => setData('taux', e.target.value)}
                                    className="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm"
                                />
                                <InputError message={errors.taux} className="mt-2" />
                            </div>
                            <div className="mt-4 text-right">
                                <Link href={isResponsable ? route('responsable.action.mesActions') : route('action.index')}>
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

