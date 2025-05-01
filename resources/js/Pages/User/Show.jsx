
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from '@inertiajs/react';

export default function Show({ auth ,  user}) {
    //console.log(responsable);
    return(

        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {`Utilisateur "${user.nom}"`}
                </h2>
            }
        >
            <Head title={`Utilisateur "${user.nom}"`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid gap-1 grid-cols-2 mt-2">
                                <div>
                                    <div>
                                        <label className="font-bold text-lg">ID d'Utilisateur'</label>
                                        <p className="mt-1">{user.id}</p>
                                    </div>
                                    <div className="mt-2">
                                        <label className="font-bold text-lg">NOM d'Utilisateur'</label>
                                        <p className="mt-1">{user.nom}</p>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <label className="font-bold text-lg"> ROLE d'Utilisateur</label>
                                        <p className="mt-1">{role.name}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="mt-4">
                                <label className="font-bold text-lg">EMAIL d'Utilisateur'</label>
                                <p className="mt-1">{user.email}</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div className="pb-12">
                        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                            <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                                <div className="p-6 text-gray-900 dark:text-gray-100">
                                <div>
                                        <label className="font-bold text-lg">Actions liées à cet utilisateur</label>
                                        {user.actions && user.actions.length > 0 ? (
                                            <ul className="list-disc list-inside mt-1">
                                                {user.actions.map((action) => (
                                                    <li key={action.id}>
                                                        <Link
                                                            href={route('action.show', action.id)}
                                                            className="text-blue-600 hover:underline"
                                                        >
                                                            {action.nom}
                                                        </Link>
                                                    </li>
                                                ))}
                                            </ul>
                                            ) : (
                                                <p className="mt-1">Aucune action liée.</p>
                                            )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    </AuthenticatedLayout>
    )
}
