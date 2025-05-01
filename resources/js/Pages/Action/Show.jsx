import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/react";

export default function Show({auth, action, queryParams = null, montantConsomme, resteAConsommer, taux_execution}) {

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {`l'Action: "${action.nom}"`}
                </h2>
            }
        >
            <Head title={`l'Action: "${action.nom}"`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid gap-1 grid-cols-2 mt-2">
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">ID d'ACTION</label>
                                        <p className="mt-1">{action.id}</p>
                                    </div>
                                    <div className="mt-2">
                                        <label className="font-bold text-lg">NOM d'ACTION</label>
                                        <p className="mt-1">{action.nom}</p>
                                    </div>
                                    <label className="font-bold text-lg mt-5">Statut</label>
                                    <p className={`mt-1 ${
                                        action.statut === 'Terminée' ? 'text-green-600' :
                                        action.statut === 'En cours' ? 'text-yellow-500' :
                                        action.statut === 'En attente' ? 'text-blue-600' :
                                        action.statut === 'A venir' ? 'text-indigo-600' :
                                        action.statut === 'En retard' ? 'text-gray-600' :
                                        'text-red-600'
                                        }`}>
                                        {action.statut}
                                    </p>

                                </div>
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">Date debut</label>
                                        <p className="mt-1">{action.date_debut}</p>
                                    </div>
                                    <div>
                                        <label className="font-bold text-lg">Date fin</label>
                                        <p className="mt-1">{action.date_fin}</p>
                                    </div>
                                    <div>
                                        <label className="font-bold text-lg">Cree quand</label>
                                        <p className="mt-1">{action.created_at}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="mt-4">
                                <label className="font-bold text-lg">Description d'ACTION</label>
                                <p className="mt-1">{action.description}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="pb-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid gap-1 grid-cols-2 mt-2">
                                <div>
                                <p className="font-bold text-lg">Responsable de l'ACTION</p>
                                <Link href={route('user.show', action.responsable.id)}>{action.responsable.nom}</Link>
                                </div>
                                <div>
                                    <label className="font-bold text-lg"> lien pour le sous axe liée</label>
                                    <Link href={route('sousaxe.show', action.sous_axe.id)}>
                                        <p className="mt-1">{action.sous_axe.nom}</p>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="pb-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid gap-1 grid-cols-2 mt-2">
                                <div>
                                    <label className="font-bold text-lg">Budget pour l'action: {action.nom}</label>
                                    {action.budget ? (
                                        <>
                                            <p className="mt-1"> <span className="font-bold">Montant alloué :</span> {action.budget.montant?.toLocaleString()} MRU</p>
                                            <p className="text-gray-700 dark:text-gray-300">
                                                <span className="font-bold">Montant consommé :</span> {montantConsomme?.toLocaleString()} MRU
                                            </p>
                                            <p className="text-gray-700 dark:text-gray-300">
                                                <span className="font-bold">Reste à consommer :</span> {resteAConsommer?.toLocaleString()} MRU
                                            </p>
                                        </>
                                    ) : (
                                        <p className="text-red-600">Aucun budget associé à cette action.</p>
                                    )}
                                </div>
                                <div>
                                    <label className="font-bold text-lg">Le taux d'exécution d'action "{action.nom}" est:</label>
                                    <p className="mt-1">{taux_execution?.taux ?? 0}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )

}
