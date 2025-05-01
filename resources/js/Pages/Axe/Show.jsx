import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";
import SousAxesTAble from "../SousAxe/SousAxesTable";
import { useForm } from "@inertiajs/react";

export default function Show({ auth, axe, sousaxes, queryParams = null, stats}) {


    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {`Axe "${axe.nom}"`}
                </h2>
            }
        >
            <Head title={`Axe "${axe.nom}"`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid gap-1 grid-cols-2 mt-2">
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">ID de l'AXE</label>
                                        <p className="mt-1">{axe.id}</p>
                                    </div>
                                    <div className="mt-2">
                                        <label className="font-bold text-lg">NOM de l'AXE</label>
                                        <p className="mt-1">{axe.nom}</p>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">Créé le</label>
                                        <p className="mt-1">{axe.created_at}</p>
                                    </div>
                                    <div>
                                        <p className="font-bold text-lg">Coordinateur d'AXE</p>
                                        <Link href={route('user.show', axe.coordinateur.id)}>{axe.coordinateur.nom}</Link>
                                    </div>
                                </div>
                            </div>
                            <div className="mt-4">
                                <label className="font-bold text-lg">Description de l'AXE</label>
                                <p className="mt-1">{axe.description}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="pb-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <SousAxesTAble sousaxes={sousaxes} queryParams={queryParams} />
                        </div>
                    </div>
                </div>
            </div>

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid gap-1 grid-cols-2 mt-2">
                                <div>
                                    <div>
                                        <h3 className="font-bold text-lg">Statistique financiere</h3>
                                    </div>
                                    <div className="mt-2">
                                        <label className="font-bold text-lg">Budget totale</label>
                                        <p className="mt-1">{stats.totalBudget.toLocaleString('fr-FR')} UM</p>
                                    </div>
                                    <div className="mt-2">
                                        <label className="font-bold text-lg">Budget restant</label>
                                        <p className="mt-1">{stats.budgetRestant.toLocaleString('fr-FR')} UM</p>
                                    </div>
                                    <div className="mt-2">
                                        <label className="font-bold text-lg">Budget consommer</label>
                                        <p className="mt-1">{stats.budgetConsomme.toLocaleString('fr-FR')} UM</p>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">Le taux moyenne d'execution</label>
                                        <p className="mt-1">{stats.tauxExecutionMoyen}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </AuthenticatedLayout>
    );
}
