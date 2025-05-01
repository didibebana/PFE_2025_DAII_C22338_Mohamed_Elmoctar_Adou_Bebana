import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import ActionTable from './ActionTable';
import { usePage } from '@inertiajs/react';

export default function Index({ auth, actions, queryParams = null, success }) {

    const user = usePage().props.auth.user;

    const isAdmin = user?.role?.nom === 'administrateur';
    const isCoordinateur = user?.role?.nom === 'coordinateur_axe';
    const isResponsable = user?.role?.nom === 'responsable_action';

    return(
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Liste des Actions
                    </h2>
                    {(isAdmin || isCoordinateur) && (<>
                    <Link className="bg-emerald-500 text-white font-bold py-1 px-3 rounded shadow transition-all hover:bg-emerald-400" href={route("action.create")}>
                        Ajouter une Action
                    </Link>
                    </>)}
                </div>
            }
        >

            <Head title="Actions" />
            {success &&
            (<div className="bg-emerald-500 py-2 px-4 text-white rounded">
                {success}
            </div>)
            }


            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <ActionTable actions={actions} queryParams={queryParams} />
                </div>
            </div>
        </AuthenticatedLayout>
    )

}
