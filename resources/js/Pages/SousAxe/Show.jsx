import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import ActionTAble from "../Action/ActionTable";

export default function Show({auth, sousaxe, actions, queryParams = null}) {

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {`Sous Axe "${sousaxe.nom}"`}
                </h2>
            }
        >
            <Head title={`Sous Axe "${sousaxe.nom}"`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid gap-1 grid-cols-2 mt-2">
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">ID du SOUS AXE</label>
                                        <p className="mt-1">{sousaxe.id}</p>
                                    </div>
                                    <div className="mt-2">
                                        <label className="font-bold text-lg">NOM du SOUS AXE</label>
                                        <p className="mt-1">{sousaxe.nom}</p>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">Cree quand</label>
                                        <p className="mt-1">{sousaxe.created_at}</p>
                                    </div>
                                    <div>
                                        <label className="font-bold text-lg">lien pour l'axe liée</label>
                                        <Link href={route('axe.show', sousaxe.axe.id)} className="mt-1"><p>{sousaxe.axe.nom}</p></Link>
                                    </div>
                                </div>
                            </div>
                            <div className="mt-4">
                                <label className="font-bold text-lg">Description du SOUS AXE</label>
                                <p className="mt-1">{sousaxe.description}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="pb-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="flex justify-between items-center p-6">
                            <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                                Actions du SOUS AXE
                            </h2>
                            <Link className="bg-emerald-500 text-white font-bold py-1 px-3 rounded shadow transition-all hover:bg-emerald-400" href={route("action.create")}>
                                Ajouter une Action
                            </Link>
                        </div>
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <ActionTAble actions={actions} queryParams={queryParams} />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )

}
